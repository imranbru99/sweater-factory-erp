<?php

namespace App\Modules\Core\Services;

use App\Models\User;
use App\Modules\Core\Enums\ApprovalStatus;
use App\Modules\Core\Exceptions\ApprovalException;
use App\Modules\Core\Models\ApprovalAction;
use App\Modules\Core\Models\ApprovalDelegation;
use App\Modules\Core\Models\ApprovalFlow;
use App\Modules\Core\Models\ApprovalRequest;
use App\Modules\Core\Models\ApprovalStep;
use App\Modules\Core\Notifications\ApprovalRequested;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tarikh\Tarikh;

class ApprovalEngine
{
    public function __construct(private readonly WorkingDayCalendar $calendar) {}

    public function submit(Model $subject, User $user): ApprovalRequest
    {
        $documentType = $this->documentType($subject);
        $flow = ApprovalFlow::query()
            ->where('document_type', $documentType)
            ->where('is_active', true)
            ->with('steps')
            ->first();

        if ($flow === null || $flow->steps->isEmpty()) {
            throw new ApprovalException('No active approval flow for '.$documentType.'.');
        }

        return DB::transaction(function () use ($subject, $user, $flow, $documentType): ApprovalRequest {
            $request = ApprovalRequest::query()->create([
                'approvable_type' => $subject->getMorphClass(),
                'approvable_id' => $subject->getKey(),
                'approval_flow_id' => $flow->id,
                'document_type' => $documentType,
                'branch_id' => $subject->getAttribute('branch_id'),
                'status' => ApprovalStatus::Pending,
                'current_step' => (int) $flow->steps->min('step_order'),
                'requested_by' => $user->id,
                'step_started_at' => now(),
            ]);

            $subject->forceFill([
                'status' => ApprovalStatus::Pending->value,
                'submitted_at' => now(),
                'requested_by' => $user->id,
            ])->save();

            $this->notifyStep($request->fresh(['flow.steps']));

            return $request->fresh(['flow.steps']);
        });
    }

    public function approve(ApprovalRequest $request, User $user, ?string $comment = null): ApprovalRequest
    {
        return $this->decide($request, $user, 'approved', $comment);
    }

    public function reject(ApprovalRequest $request, User $user, ?string $comment = null): ApprovalRequest
    {
        return $this->decide($request, $user, 'rejected', $comment);
    }

    public function decide(ApprovalRequest $request, User $user, string $decision, ?string $comment = null): ApprovalRequest
    {
        $request->loadMissing('flow.steps', 'approvable');
        $step = $this->currentStep($request);

        if ($request->status !== ApprovalStatus::Pending) {
            throw new ApprovalException('This note is not waiting for approval.');
        }

        if (! $this->userMayAct($user, $request, $step)) {
            throw new ApprovalException('You cannot act on this approval step.');
        }

        return DB::transaction(function () use ($request, $user, $step, $decision, $comment): ApprovalRequest {
            ApprovalAction::query()->create([
                'approval_request_id' => $request->id,
                'step_order' => $step->step_order,
                'actor_id' => $user->id,
                'decision' => $decision,
                'comment' => $comment,
                'delegated_from_id' => $this->delegatorId($user, $request, $step),
                'acted_at' => now(),
            ]);

            $subject = $request->approvable;

            if ($decision === 'rejected') {
                $request->update(['status' => ApprovalStatus::Rejected]);
                $subject?->forceFill(['status' => ApprovalStatus::Rejected->value])->save();

                return $request->fresh();
            }

            $next = $request->flow->steps
                ->sortBy('step_order')
                ->first(fn (ApprovalStep $candidate): bool => $candidate->step_order > $step->step_order);

            if ($next === null) {
                $request->update(['status' => ApprovalStatus::Approved, 'escalated_at' => null]);
                $subject?->forceFill(['status' => ApprovalStatus::Approved->value])->save();

                return $request->fresh();
            }

            $request->update([
                'current_step' => $next->step_order,
                'step_started_at' => now(),
                'escalated_at' => null,
            ]);

            $this->notifyStep($request->fresh(['flow.steps']));

            return $request->fresh(['flow.steps']);
        });
    }

    public function escalateDue(): int
    {
        $count = 0;
        $pending = ApprovalRequest::query()
            ->where('status', ApprovalStatus::Pending)
            ->whereNull('escalated_at')
            ->with('flow.steps')
            ->get();

        foreach ($pending as $request) {
            $step = $this->currentStep($request);
            if ($step->escalate_after_hours === null || $request->step_started_at === null) {
                continue;
            }

            $deadline = $this->calendar->addWorkingMinutes(
                Tarikh::parse($request->step_started_at),
                $step->escalate_after_hours * 60,
                $request->branch_id,
            );
            $now = Tarikh::now((string) config('factory.timezone'));

            if ($now->isBefore($deadline)) {
                continue;
            }

            $request->update(['escalated_at' => now()]);
            $this->notifyStep($request->fresh(['flow.steps']), escalated: true);
            $count++;
        }

        return $count;
    }

    public function userMayAct(User $user, ApprovalRequest $request, ApprovalStep $step): bool
    {
        if ($request->status !== ApprovalStatus::Pending || $request->current_step !== $step->step_order) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($this->matchesStep($user, $step)) {
            return true;
        }

        if ($this->delegatorId($user, $request, $step) !== null) {
            return true;
        }

        return $request->escalated_at !== null
            && $step->escalate_to_role_id !== null
            && $user->roles()->where('roles.id', $step->escalate_to_role_id)->exists();
    }

    private function matchesStep(User $user, ApprovalStep $step): bool
    {
        if ($step->role_id !== null) {
            return $user->roles()->where('roles.id', $step->role_id)->exists();
        }

        return $step->permission !== null && $user->hasPermission($step->permission);
    }

    private function delegatorId(User $user, ApprovalRequest $request, ApprovalStep $step): ?int
    {
        $delegations = ApprovalDelegation::query()
            ->where('to_user_id', $user->id)
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->where(function ($query) use ($request): void {
                $query->whereNull('document_type')->orWhere('document_type', $request->document_type);
            })
            ->get();

        foreach ($delegations as $delegation) {
            $from = User::query()->find($delegation->from_user_id);
            if ($from instanceof User && $this->matchesStep($from, $step)) {
                return $from->id;
            }
        }

        return null;
    }

    private function currentStep(ApprovalRequest $request): ApprovalStep
    {
        $step = $request->flow?->steps?->firstWhere('step_order', $request->current_step);

        if (! $step instanceof ApprovalStep) {
            throw new ApprovalException('Approval step '.$request->current_step.' is missing.');
        }

        return $step;
    }

    private function notifyStep(ApprovalRequest $request, bool $escalated = false): void
    {
        $step = $this->currentStep($request);
        $roleId = $escalated ? $step->escalate_to_role_id : $step->role_id;

        if ($roleId === null) {
            return;
        }

        $users = User::query()
            ->where('is_active', true)
            ->whereHas('roles', fn ($query) => $query->where('roles.id', $roleId))
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        Notification::send($users, new ApprovalRequested($request, $escalated));
    }

    private function documentType(Model $subject): string
    {
        if (! method_exists($subject, 'approvalDocumentType')) {
            throw new ApprovalException($subject::class.' cannot enter an approval flow.');
        }

        return $subject->approvalDocumentType();
    }
}
