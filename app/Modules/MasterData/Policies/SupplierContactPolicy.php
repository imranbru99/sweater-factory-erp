<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class SupplierContactPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'SupplierContact';
    }
}
