<?php

namespace App\Modules\Core\Policies;

class SettingPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Setting';
    }
}
