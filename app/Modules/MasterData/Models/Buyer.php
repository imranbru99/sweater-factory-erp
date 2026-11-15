<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Buyer extends MasterModel
{
    use BelongsToBranch, HasUniversalSlug;

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'credit_days' => 'integer',
            'commission_percent' => 'decimal:4',
        ]);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(BuyerContact::class);
    }

    public function agents(): HasMany
    {
        return $this->hasMany(BuyingAgent::class);
    }

    public static function fieldMap(): array
    {
        return [
            'branch_id' => ['type' => 'select', 'relation' => 'branch', 'required' => true],
            'code' => ['type' => 'text', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'brand' => ['type' => 'text'],
            'country' => ['type' => 'text'],
            'payment_terms' => ['type' => 'text'],
            'incoterm' => ['type' => 'text'],
            'currency_code' => ['type' => 'text', 'required' => true],
            'credit_days' => ['type' => 'number'],
            'commission_percent' => ['type' => 'decimal'],
            'compliance_requirements' => ['type' => 'textarea'],
            'is_active' => ['type' => 'toggle'],
        ];
    }
}
