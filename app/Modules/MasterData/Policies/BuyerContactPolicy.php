<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class BuyerContactPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'BuyerContact';
    }
}
