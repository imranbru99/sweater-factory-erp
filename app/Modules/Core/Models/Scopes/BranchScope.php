<?php

namespace App\Modules\Core\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if (! $user instanceof User || $user->seesAllBranches()) {
            return;
        }

        $ids = $user->accessibleBranchIds();

        if ($ids === []) {
            $builder->whereRaw('1 = 0');

            return;
        }

        $builder->whereIn($model->getTable().'.branch_id', $ids);
    }
}
