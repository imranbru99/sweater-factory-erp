<?php

namespace App\Modules\MasterData\Models;

class Tax extends MasterModel
{

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'rate' => 'decimal:4',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text'],
            'tax_type' => ['type' => 'text', 'required' => true],
            'rate' => ['type' => 'decimal', 'required' => true],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
