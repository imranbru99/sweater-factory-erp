<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitConversion extends MasterModel
{
    protected function casts(): array
    {
        return ['factor' => 'decimal:8'];
    }

    public function fromUnit(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'from_unit_id');
    }

    public function toUnit(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'to_unit_id');
    }

    public static function fieldMap(): array
    {
        return [
            'from_unit_id' => ['type' => 'select', 'relation' => 'fromUnit', 'title' => 'code', 'required' => true],
            'to_unit_id' => ['type' => 'select', 'relation' => 'toUnit', 'title' => 'code', 'required' => true],
            'factor' => ['type' => 'decimal', 'required' => true],
        ];
    }
}
