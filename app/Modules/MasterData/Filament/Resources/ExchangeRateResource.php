<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\ExchangeRate;
use App\Modules\MasterData\Filament\Resources\ExchangeRateResource\Pages\CreateExchangeRate;
use App\Modules\MasterData\Filament\Resources\ExchangeRateResource\Pages\EditExchangeRate;
use App\Modules\MasterData\Filament\Resources\ExchangeRateResource\Pages\ListExchangeRates;
use App\Modules\MasterData\Filament\Resources\ExchangeRateResource\Pages\ViewExchangeRate;

class ExchangeRateResource extends MasterResource
{
    protected static ?string $model = ExchangeRate::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    public static function getPages(): array
    {
        return [
            'index' => ListExchangeRates::route('/'),
            'create' => CreateExchangeRate::route('/create'),
            'view' => ViewExchangeRate::route('/{record}'),
            'edit' => EditExchangeRate::route('/{record}/edit'),
        ];
    }
}
