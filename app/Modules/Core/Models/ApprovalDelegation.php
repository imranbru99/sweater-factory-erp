<?php

namespace App\Modules\Core\Models;

use App\Modules\Core\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class ApprovalDelegation extends Model
{
    use Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
