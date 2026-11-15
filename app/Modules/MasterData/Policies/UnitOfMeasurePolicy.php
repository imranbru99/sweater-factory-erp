<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class UnitOfMeasurePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'UnitOfMeasure';
    }
}
