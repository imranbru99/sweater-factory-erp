<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class SizePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Size';
    }
}
