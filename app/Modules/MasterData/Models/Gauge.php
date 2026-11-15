<?php

namespace App\Modules\MasterData\Models;

class Gauge extends MasterModel
{

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'gauge_number' => 'decimal:2',
        ]);
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'gauge_number' => ['type' => 'decimal', 'required' => true],
            'description' => ['type' => 'textarea'],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
