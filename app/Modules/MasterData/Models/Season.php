<?php

namespace App\Modules\MasterData\Models;

class Season extends MasterModel
{

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'year' => 'integer',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text'],
            'year' => ['type' => 'number'],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
