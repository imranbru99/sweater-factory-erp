<?php

namespace App\Modules\Core\Policies;

class BranchPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Branch';
    }
}
