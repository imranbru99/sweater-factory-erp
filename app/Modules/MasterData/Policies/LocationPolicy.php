<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class LocationPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Location';
    }
}
