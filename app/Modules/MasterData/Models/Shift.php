<?php

namespace App\Modules\MasterData\Models;

class Shift extends MasterModel
{

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'break_minutes' => 'integer',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text'],
            'starts_at' => ['type' => 'time', 'required' => true],
            'ends_at' => ['type' => 'time', 'required' => true],
            'break_minutes' => ['type' => 'number', 'required' => true],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
