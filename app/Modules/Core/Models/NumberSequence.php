<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NumberSequence extends Model
{
    protected $guarded = ['id'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
