<?php

namespace App\Modules\Core\Policies;

class NotificationTemplatePolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'NotificationTemplate';
    }
}
