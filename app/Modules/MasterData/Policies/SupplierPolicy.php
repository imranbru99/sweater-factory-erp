<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class SupplierPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Supplier';
    }
}
