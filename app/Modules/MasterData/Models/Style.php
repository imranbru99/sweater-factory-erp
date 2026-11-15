<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Core\Models\Concerns\BelongsToBranch;
use App\Modules\MasterData\Enums\StyleStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Style extends MasterModel
{
    use BelongsToBranch, HasUniversalSlug;

    protected function casts(): array
    {
        return [
            'target_weight_grams' => 'decimal:2',
            'status' => StyleStatus::class,
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function gauge(): BelongsTo
    {
        return $this->belongsTo(Gauge::class);
    }

    public function stitchType(): BelongsTo
    {
        return $this->belongsTo(StitchType::class);
    }

    public function yarnType(): BelongsTo
    {
        return $this->belongsTo(YarnType::class);
    }

    public static function fieldMap(): array
    {
        return [
            'branch_id' => ['type' => 'select', 'relation' => 'branch', 'required' => true],
            'buyer_id' => ['type' => 'select', 'relation' => 'buyer'],
            'season_id' => ['type' => 'select', 'relation' => 'season'],
            'department_id' => ['type' => 'select', 'relation' => 'department'],
            'product_type_id' => ['type' => 'select', 'relation' => 'productType'],
            'gauge_id' => ['type' => 'select', 'relation' => 'gauge'],
            'stitch_type_id' => ['type' => 'select', 'relation' => 'stitchType'],
            'yarn_type_id' => ['type' => 'select', 'relation' => 'yarnType'],
            'code' => ['type' => 'text', 'required' => true],
            'name' => ['type' => 'text', 'required' => true],
            'name_bn' => ['type' => 'text', 'label' => 'Name (Bangla)'],
            'description' => ['type' => 'textarea'],
            'target_weight_grams' => ['type' => 'decimal'],
            'status' => ['type' => 'enum', 'enum' => StyleStatus::class, 'required' => true],
        ];
    }
}
