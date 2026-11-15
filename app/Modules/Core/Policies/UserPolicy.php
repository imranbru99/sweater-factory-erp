<?php

namespace App\Modules\Core\Policies;

class UserPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'User';
    }
}
