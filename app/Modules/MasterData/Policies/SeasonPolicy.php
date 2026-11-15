<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class SeasonPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Season';
    }
}
