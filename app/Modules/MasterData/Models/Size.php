<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends MasterModel
{
    public function sizeGroup(): BelongsTo
    {
        return $this->belongsTo(SizeGroup::class);
    }

    public static function fieldMap(): array
    {
        return [
            'size_group_id' => ['type' => 'select', 'relation' => 'sizeGroup', 'title' => 'name', 'required' => true],
            'code' => ['type' => 'text', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'sort' => ['type' => 'number'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}

