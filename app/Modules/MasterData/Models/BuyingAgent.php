<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyingAgent extends MasterModel
{
    protected function casts(): array
    {
        return array_merge(parent::casts(), ['commission_percent' => 'decimal:4']);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public static function fieldMap(): array
    {
        return [
            'buyer_id' => ['type' => 'select', 'relation' => 'buyer'],
            'name' => ['type' => 'text', 'required' => true],
            'country' => ['type' => 'text'],
            'commission_percent' => ['type' => 'decimal'],
            'phone' => ['type' => 'text'],
            'email' => ['type' => 'text'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
