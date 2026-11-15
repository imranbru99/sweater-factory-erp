<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class CurrencyPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Currency';
    }
}
