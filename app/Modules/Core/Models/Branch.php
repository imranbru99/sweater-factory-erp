<?php

namespace App\Modules\Core\Models;

use App\Models\User;
use App\Modules\Core\Enums\BranchType;
use App\Modules\Core\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use Auditable, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => BranchType::class,
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('is_default');
    }

    public static function fieldMap(): array
    {
        return [
            'code' => ['type' => 'text', 'required' => true, 'unique' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'type' => ['type' => 'enum', 'enum' => BranchType::class, 'required' => true],
            'parent_id' => ['type' => 'select', 'relation' => 'parent', 'title' => 'name'],
            'address' => ['type' => 'text'],
            'city' => ['type' => 'text'],
            'district' => ['type' => 'text'],
            'phone' => ['type' => 'text'],
            'email' => ['type' => 'text'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
