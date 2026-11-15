<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class UnitConversionPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'UnitConversion';
    }
}
