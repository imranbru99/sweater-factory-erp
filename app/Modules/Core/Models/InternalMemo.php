<?php

namespace App\Modules\Core\Models;

use App\Models\User;
use App\Modules\Core\Enums\ApprovalStatus;
use App\Modules\Core\Models\Concerns\Auditable;
use App\Modules\Core\Models\Concerns\BelongsToBranch;
use App\Modules\Core\Models\Scopes\BranchScope;
use App\Modules\Core\Services\DocumentNumberService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalMemo extends Model
{
    use Auditable, BelongsToBranch, SoftDeletes;

    protected $guarded = ['id'];

    protected $attributes = [
        'document_type' => 'internal_memo',
        'currency_code' => 'BDT',
        'status' => 'draft',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
            'status' => ApprovalStatus::class,
            'submitted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $memo): void {
            if (blank($memo->number) && $memo->branch_id) {
                $branch = Branch::query()->withoutGlobalScope(BranchScope::class)->find($memo->branch_id);
                if ($branch instanceof Branch) {
                    $memo->number = app(DocumentNumberService::class)->next('internal_memo', $branch);
                }
            }
        });
    }

    public function approvalDocumentType(): string
    {
        return 'internal_memo';
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvalRequests(): MorphMany
    {
        return $this->morphMany(ApprovalRequest::class, 'approvable');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public static function fieldMap(): array
    {
        return [
            'branch_id' => ['type' => 'select', 'relation' => 'branch', 'required' => true],
            'number' => ['type' => 'text', 'disabled' => true],
            'subject' => ['type' => 'text', 'required' => true],
            'body' => ['type' => 'textarea', 'required' => true],
            'amount' => ['type' => 'money'],
            'currency_code' => ['type' => 'text', 'required' => true, 'default' => 'BDT'],
            'status' => ['type' => 'enum', 'enum' => ApprovalStatus::class, 'disabled' => true],
        ];
    }
}
