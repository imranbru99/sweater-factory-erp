<?php

namespace App\Modules\Core\Models;

use App\Modules\Core\Exceptions\ModuleException;
use Illuminate\Database\Eloquent\Model;

class ModuleRecord extends Model
{
    protected $table = 'modules';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_core' => 'boolean',
            'is_enabled' => 'boolean',
            'sort' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (self $module): void {
            if ($module->is_core && $module->is_enabled === false) {
                throw new ModuleException('Core module ['.$module->key.'] cannot be disabled.');
            }
        });
    }
}
