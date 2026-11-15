<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public static function fieldMap(): array
    {
        return [
            'key' => ['type' => 'text', 'required' => true],
            'channel' => ['type' => 'text', 'required' => true],
            'locale' => ['type' => 'text', 'required' => true],
            'subject' => ['type' => 'text'],
            'body' => ['type' => 'textarea', 'required' => true],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
