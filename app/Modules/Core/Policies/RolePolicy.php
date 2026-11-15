<?php

namespace App\Modules\Core\Policies;

class RolePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Role';
    }
}
