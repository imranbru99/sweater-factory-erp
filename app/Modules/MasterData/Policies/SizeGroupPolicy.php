<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class SizeGroupPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'SizeGroup';
    }
}
