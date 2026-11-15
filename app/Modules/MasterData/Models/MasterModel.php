<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class MasterModel extends Model
{
    use Auditable, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort' => 'integer',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function fieldMap(): array
    {
        return [];
    }
}
