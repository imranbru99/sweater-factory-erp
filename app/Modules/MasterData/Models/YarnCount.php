<?php

namespace App\Modules\MasterData\Models;

use App\Modules\MasterData\Enums\YarnCountSystem;
class YarnCount extends MasterModel
{

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'count_value' => 'decimal:2',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text'],
            'system' => ['type' => 'enum', 'required' => true, 'enum' => YarnCountSystem::class],
            'count_value' => ['type' => 'decimal', 'required' => true],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
