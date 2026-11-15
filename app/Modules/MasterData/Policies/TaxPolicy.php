<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class TaxPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Tax';
    }
}
