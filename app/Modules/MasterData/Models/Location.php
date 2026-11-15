<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends MasterModel
{
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public static function fieldMap(): array
    {
        return [
            'warehouse_id' => ['type' => 'select', 'relation' => 'warehouse', 'required' => true],
            'rack' => ['type' => 'text', 'required' => true],
            'bin' => ['type' => 'text'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}

