<?php

namespace App\Modules\Core\Actions;

use App\Models\User;
use App\Modules\Core\Enums\ApprovalStatus;
use App\Modules\Core\Exceptions\ApprovalException;
use App\Modules\Core\Models\ApprovalRequest;
use App\Modules\Core\Models\InternalMemo;
use App\Modules\Core\Services\ApprovalEngine;

class DecideMemo
{
    public function __construct(private readonly ApprovalEngine $engine) {}

    public function handle(InternalMemo $memo, User $user, string $decision, ?string $comment = null): ApprovalRequest
    {
        $request = $memo->approvalRequests()
            ->where('status', ApprovalStatus::Pending)
            ->latest('id')
            ->first();

        if (! $request instanceof ApprovalRequest) {
            throw new ApprovalException('This note sheet is not waiting for a decision.');
        }

        return $decision === 'rejected'
            ? $this->engine->reject($request, $user, $comment)
            : $this->engine->approve($request, $user, $comment);
    }
}
