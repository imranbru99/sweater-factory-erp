<?php

namespace App\Modules\MasterData\Models;

class UnitOfMeasure extends MasterModel
{

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'dimension' => ['type' => 'text', 'required' => true],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
