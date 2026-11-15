<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class StylePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Style';
    }
}
