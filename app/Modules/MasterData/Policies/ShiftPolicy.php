<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class ShiftPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Shift';
    }
}
