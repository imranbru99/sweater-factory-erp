<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Gauge;
use App\Modules\MasterData\Filament\Resources\GaugeResource\Pages\CreateGauge;
use App\Modules\MasterData\Filament\Resources\GaugeResource\Pages\EditGauge;
use App\Modules\MasterData\Filament\Resources\GaugeResource\Pages\ListGauges;
use App\Modules\MasterData\Filament\Resources\GaugeResource\Pages\ViewGauge;

class GaugeResource extends MasterResource
{
    protected static ?string $model = Gauge::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Yarn & Knit';

    public static function getPages(): array
    {
        return [
            'index' => ListGauges::route('/'),
            'create' => CreateGauge::route('/create'),
            'view' => ViewGauge::route('/{record}'),
            'edit' => EditGauge::route('/{record}/edit'),
        ];
    }
}
