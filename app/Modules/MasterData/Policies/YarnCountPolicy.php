<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class YarnCountPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'YarnCount';
    }
}
