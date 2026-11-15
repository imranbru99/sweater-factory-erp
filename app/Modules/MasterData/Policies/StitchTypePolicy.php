<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class StitchTypePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'StitchType';
    }
}
