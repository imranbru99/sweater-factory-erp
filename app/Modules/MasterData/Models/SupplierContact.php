<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierContact extends MasterModel
{
    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public static function fieldMap(): array
    {
        return [
            'supplier_id' => ['type' => 'select', 'relation' => 'supplier', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'designation' => ['type' => 'text'],
            'phone' => ['type' => 'text'],
            'email' => ['type' => 'text'],
            'is_primary' => ['type' => 'toggle', 'default' => false],
        ];
    }
}
