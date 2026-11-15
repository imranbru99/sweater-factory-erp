<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Concerns\BelongsToBranch;
use App\Modules\MasterData\Enums\MachineStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Machine extends MasterModel
{
    use BelongsToBranch;

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'width_inches' => 'decimal:2',
            'year_made' => 'integer',
            'needles' => 'integer',
            'installed_on' => 'date',
            'status' => MachineStatus::class,
        ]);
    }

    public function gauge(): BelongsTo
    {
        return $this->belongsTo(Gauge::class);
    }

    public static function fieldMap(): array
    {
        return [
            'branch_id' => ['type' => 'select', 'relation' => 'branch', 'required' => true],
            'gauge_id' => ['type' => 'select', 'relation' => 'gauge'],
            'code' => ['type' => 'text', 'required' => true],
            'brand' => ['type' => 'text', 'required' => true],
            'model' => ['type' => 'text'],
            'width_inches' => ['type' => 'decimal'],
            'year_made' => ['type' => 'number'],
            'serial_number' => ['type' => 'text'],
            'status' => ['type' => 'enum', 'enum' => MachineStatus::class, 'required' => true],
            'needles' => ['type' => 'number'],
            'installed_on' => ['type' => 'date'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
