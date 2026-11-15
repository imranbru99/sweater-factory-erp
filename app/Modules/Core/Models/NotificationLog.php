<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime'];
    }
}
