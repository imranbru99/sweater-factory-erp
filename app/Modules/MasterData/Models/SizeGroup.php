<?php

namespace App\Modules\MasterData\Models;

class SizeGroup extends MasterModel
{

    public function sizes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Size::class);
    }

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
