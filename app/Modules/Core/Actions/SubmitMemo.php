<?php

namespace App\Modules\Core\Actions;

use App\Models\User;
use App\Modules\Core\Enums\ApprovalStatus;
use App\Modules\Core\Exceptions\ApprovalException;
use App\Modules\Core\Models\ApprovalRequest;
use App\Modules\Core\Models\InternalMemo;
use App\Modules\Core\Services\ApprovalEngine;

class SubmitMemo
{
    public function __construct(private readonly ApprovalEngine $engine) {}

    public function handle(InternalMemo $memo, User $user): ApprovalRequest
    {
        if ($memo->status !== ApprovalStatus::Draft) {
            throw new ApprovalException('Only a draft note sheet can be submitted.');
        }

        return $this->engine->submit($memo, $user);
    }
}
