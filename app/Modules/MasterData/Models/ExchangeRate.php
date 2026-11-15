<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExchangeRate extends MasterModel
{
    protected function casts(): array
    {
        return [
            'rate' => 'decimal:8',
            'effective_date' => 'date',
        ];
    }

    public static function fieldMap(): array
    {
        return [
            'from_currency' => ['type' => 'text', 'required' => true],
            'to_currency' => ['type' => 'text', 'required' => true],
            'rate' => ['type' => 'decimal', 'required' => true],
            'effective_date' => ['type' => 'date', 'required' => true],
            'source' => ['type' => 'text'],
        ];
    }
}

