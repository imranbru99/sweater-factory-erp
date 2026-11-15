<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class BuyerPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Buyer';
    }
}
