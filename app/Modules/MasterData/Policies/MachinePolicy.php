<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class MachinePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Machine';
    }
}
