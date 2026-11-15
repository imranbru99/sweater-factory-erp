<?php

namespace App\Modules\MasterData\Models;

class Operation extends MasterModel
{

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'smv' => 'decimal:4',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text'],
            'stage' => ['type' => 'text', 'required' => true],
            'smv' => ['type' => 'decimal', 'required' => true],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
