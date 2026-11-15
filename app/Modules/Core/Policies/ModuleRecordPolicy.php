<?php

namespace App\Modules\Core\Policies;

class ModuleRecordPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'ModuleRecord';
    }
}
