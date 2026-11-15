<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Exceptions\UnitConversionException;
use App\Modules\MasterData\Models\UnitConversion;
use App\Modules\MasterData\Models\UnitOfMeasure;

class UnitConverter
{
    public function convert(string $quantity, string $fromCode, string $toCode): string
    {
        $quantity = bcadd($quantity, '0', 6);

        if ($fromCode === $toCode) {
            return $quantity;
        }

        $from = UnitOfMeasure::query()->where('code', $fromCode)->firstOrFail();
        $to = UnitOfMeasure::query()->where('code', $toCode)->firstOrFail();

        $direct = UnitConversion::query()
            ->where('from_unit_id', $from->id)
            ->where('to_unit_id', $to->id)
            ->first();

        if ($direct !== null) {
            return bcadd(bcmul($quantity, (string) $direct->factor, 8), '0', 6);
        }

        $inverse = UnitConversion::query()
            ->where('from_unit_id', $to->id)
            ->where('to_unit_id', $from->id)
            ->first();

        if ($inverse !== null && bccomp((string) $inverse->factor, '0', 8) === 1) {
            return bcadd(bcdiv($quantity, (string) $inverse->factor, 8), '0', 6);
        }

        throw new UnitConversionException(
            "No fixed conversion from {$fromCode} to {$toCode}. Cone, bag and carton weights belong on the yarn lot, because a cone is not a constant weight."
        );
    }
}
