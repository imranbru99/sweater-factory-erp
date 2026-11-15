<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class BuyingAgentPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'BuyingAgent';
    }
}
