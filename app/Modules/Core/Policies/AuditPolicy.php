<?php

namespace App\Modules\Core\Policies;

class AuditPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'Audit';
    }
}
