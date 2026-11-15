<?php

namespace App\Modules\MasterData\Models;

class YarnType extends MasterModel
{

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text'],
            'fibre' => ['type' => 'text'],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
