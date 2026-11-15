<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'value' => 'json',
            'is_public' => 'boolean',
        ];
    }

    public static function getValue(string $group, string $key, mixed $default = null, string $scope = 'global'): mixed
    {
        if (! Schema::hasTable('settings')) {
            return $default;
        }

        $row = static::query()->where(compact('group', 'key', 'scope'))->first();

        return $row?->value ?? $default;
    }

    public static function putValue(string $group, string $key, mixed $value, string $scope = 'global'): void
    {
        static::query()->updateOrCreate(
            ['group' => $group, 'key' => $key, 'scope' => $scope],
            ['value' => $value],
        );
    }
}
