<?php

namespace App\Modules\Core\Models;

use App\Modules\Core\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalFlow extends Model
{
    use Auditable, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class)->orderBy('step_order');
    }

    public static function fieldMap(): array
    {
        return [
            'document_type' => ['type' => 'text', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
