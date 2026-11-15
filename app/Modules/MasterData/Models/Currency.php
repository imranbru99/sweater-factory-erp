<?php

namespace App\Modules\MasterData\Models;

class Currency extends MasterModel
{

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'decimal_places' => 'integer',
            'is_base' => 'boolean',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'symbol' => ['type' => 'text', 'required' => true],
            'decimal_places' => ['type' => 'number', 'required' => true],
            'is_base' => ['type' => 'toggle', 'default' => false],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
