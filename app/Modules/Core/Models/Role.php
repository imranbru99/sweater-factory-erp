<?php

namespace App\Modules\Core\Models;

use App\Models\User;
use App\Modules\Core\Enums\UserType;
use App\Modules\Core\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use Auditable, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'sees_all_branches' => 'boolean',
            'requires_two_factor' => 'boolean',
            'level' => 'integer',
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public static function fieldMap(): array
    {
        return [
            'name' => ['type' => 'text', 'required' => true],
            'slug' => ['type' => 'text', 'required' => true, 'unique' => true],
            'panel' => ['type' => 'text', 'required' => true],
            'user_type' => ['type' => 'enum', 'enum' => UserType::class, 'required' => true],
            'description' => ['type' => 'textarea'],
            'sees_all_branches' => ['type' => 'toggle', 'default' => false],
            'requires_two_factor' => ['type' => 'toggle', 'default' => false],
            'level' => ['type' => 'number', 'required' => true],
        ];
    }
}
