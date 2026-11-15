<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyerContact extends MasterModel
{
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public static function fieldMap(): array
    {
        return [
            'buyer_id' => ['type' => 'select', 'relation' => 'buyer', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'designation' => ['type' => 'text'],
            'phone' => ['type' => 'text'],
            'email' => ['type' => 'text'],
            'is_primary' => ['type' => 'toggle', 'default' => false],
        ];
    }
}
