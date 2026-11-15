<?php

namespace App\Modules\MasterData\Models;

class Ply extends MasterModel
{

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'ply_count' => 'integer',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text'],
            'ply_count' => ['type' => 'number', 'required' => true],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
