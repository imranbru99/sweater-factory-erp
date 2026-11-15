<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class GaugePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Gauge';
    }
}
