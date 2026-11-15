<?php

namespace App\Modules\Core\Models\Concerns;

use App\Models\User;
use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBranch
{
    public static function bootBelongsToBranch(): void
    {
        static::addGlobalScope(new BranchScope);

        static::creating(function (self $model): void {
            if (blank($model->getAttribute('branch_id'))) {
                $user = auth()->user();
                if ($user instanceof User) {
                    $model->setAttribute('branch_id', $user->currentBranchId());
                }
            }
        });
    }

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
