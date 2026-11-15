<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class ExchangeRatePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'ExchangeRate';
    }
}
