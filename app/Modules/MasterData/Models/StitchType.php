<?php

namespace App\Modules\MasterData\Models;

class StitchType extends MasterModel
{

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
