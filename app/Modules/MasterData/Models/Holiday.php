<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Holiday extends MasterModel
{
    protected function casts(): array
    {
        return array_merge(parent::casts(), ['holiday_date' => 'date']);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public static function fieldMap(): array
    {
        return [
            'holiday_date' => ['type' => 'date', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'branch_id' => ['type' => 'select', 'relation' => 'branch'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}

