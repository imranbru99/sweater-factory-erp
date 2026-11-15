<?php

namespace App\Modules\MasterData\Policies;

use App\Modules\Core\Policies\FactoryPolicy;

class HsCodePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'HsCode';
    }
}
