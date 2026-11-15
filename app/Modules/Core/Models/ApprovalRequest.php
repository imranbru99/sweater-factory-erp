<?php

namespace App\Modules\Core\Models;

use App\Models\User;
use App\Modules\Core\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApprovalRequest extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => ApprovalStatus::class,
            'step_started_at' => 'datetime',
            'escalated_at' => 'datetime',
            'current_step' => 'integer',
        ];
    }

    public function flow(): BelongsTo
    {
        return $this->belongsTo(ApprovalFlow::class, 'approval_flow_id');
    }

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function actions(): HasMany
    {
        return $this->hasMany(ApprovalAction::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
