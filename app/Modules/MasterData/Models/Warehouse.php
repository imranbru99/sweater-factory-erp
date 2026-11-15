<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends MasterModel
{
    use BelongsToBranch;

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'type' => \App\Modules\MasterData\Enums\WarehouseType::class,
            'is_bonded' => 'boolean',
        ]);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public static function fieldMap(): array
    {
        return [
            'branch_id' => ['type' => 'select', 'relation' => 'branch', 'required' => true],
            'code' => ['type' => 'text', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'type' => ['type' => 'enum', 'enum' => \App\Modules\MasterData\Enums\WarehouseType::class, 'required' => true],
            'is_bonded' => ['type' => 'toggle', 'default' => false],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}

