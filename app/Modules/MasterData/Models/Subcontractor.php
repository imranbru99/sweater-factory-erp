<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Concerns\BelongsToBranch;

class Subcontractor extends MasterModel
{
    use BelongsToBranch;

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $model->processes ??= ['knitting'];
        });
    }

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'processes' => 'array',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'branch_id' => ['type' => 'select', 'relation' => 'branch', 'required' => true],
            'code' => ['type' => 'text', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'phone' => ['type' => 'text'],
            'email' => ['type' => 'text'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
