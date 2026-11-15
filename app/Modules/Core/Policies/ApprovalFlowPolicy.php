<?php

namespace App\Modules\Core\Policies;

class ApprovalFlowPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'ApprovalFlow';
    }
}
