<?php

namespace App\Modules\MasterData\Models;

use App\Modules\MasterData\Enums\DefectSeverity;
class DefectCode extends MasterModel
{

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text'],
            'category' => ['type' => 'text', 'required' => true],
            'severity' => ['type' => 'enum', 'required' => true, 'enum' => DefectSeverity::class],
            'is_active' => ['type' => 'toggle'],
            'sort' => ['type' => 'number'],
        ];
    }
}
