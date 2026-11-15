<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class PlyPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Ply';
    }
}
