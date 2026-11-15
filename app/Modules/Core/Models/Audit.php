<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function fieldMap(): array
    {
        return [
            'event' => ['type' => 'text'],
            'auditable_type' => ['type' => 'text'],
            'auditable_id' => ['type' => 'number'],
            'created_at' => ['type' => 'text'],
        ];
    }
}
