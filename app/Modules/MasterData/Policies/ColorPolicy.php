<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class ColorPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Color';
    }
}
