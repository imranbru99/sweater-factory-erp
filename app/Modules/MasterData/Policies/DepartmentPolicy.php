<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class DepartmentPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Department';
    }
}
