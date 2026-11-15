<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class SubcontractorPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Subcontractor';
    }
}
