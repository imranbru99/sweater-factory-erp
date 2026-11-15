<?php

namespace App\Modules\Core\Policies;

use App\Models\User;

abstract class FactoryPolicy
{
    abstract protected function modelLabel(): string;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-any '.$this->modelLabel());
    }

    public function view(User $user, mixed $model = null): bool
    {
        return $user->hasPermission('view '.$this->modelLabel());
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create '.$this->modelLabel());
    }

    public function update(User $user, mixed $model = null): bool
    {
        return $user->hasPermission('update '.$this->modelLabel());
    }

    public function delete(User $user, mixed $model = null): bool
    {
        return $user->hasPermission('delete '.$this->modelLabel());
    }

    public function restore(User $user, mixed $model = null): bool
    {
        return $user->hasPermission('restore '.$this->modelLabel());
    }

    public function forceDelete(User $user, mixed $model = null): bool
    {
        return $user->hasPermission('force-delete '.$this->modelLabel());
    }

    public function export(User $user): bool
    {
        return $user->hasPermission('export '.$this->modelLabel());
    }
}
