<?php

namespace App\Modules\Core\Policies;

class InternalMemoPolicy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return 'InternalMemo';
    }
}
