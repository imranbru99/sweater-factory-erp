<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class WarehousePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Warehouse';
    }
}
