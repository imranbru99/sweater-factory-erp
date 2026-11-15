<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Concerns\BelongsToBranch;
use App\Modules\MasterData\Enums\SupplierType;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends MasterModel
{
    use BelongsToBranch;

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'type' => SupplierType::class,
        ]);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(SupplierContact::class);
    }

    public static function fieldMap(): array
    {
        return [
            'branch_id' => ['type' => 'select', 'relation' => 'branch', 'required' => true],
            'code' => ['type' => 'text', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'type' => ['type' => 'enum', 'enum' => SupplierType::class, 'required' => true],
            'country' => ['type' => 'text'],
            'payment_terms' => ['type' => 'text'],
            'currency_code' => ['type' => 'text', 'required' => true],
            'phone' => ['type' => 'text'],
            'email' => ['type' => 'text'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
