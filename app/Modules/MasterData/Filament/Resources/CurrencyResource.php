<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Currency;
use App\Modules\MasterData\Filament\Resources\CurrencyResource\Pages\CreateCurrency;
use App\Modules\MasterData\Filament\Resources\CurrencyResource\Pages\EditCurrency;
use App\Modules\MasterData\Filament\Resources\CurrencyResource\Pages\ListCurrencies;
use App\Modules\MasterData\Filament\Resources\CurrencyResource\Pages\ViewCurrency;

class CurrencyResource extends MasterResource
{
    protected static ?string $model = Currency::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Master data';

    public static function getPages(): array
    {
        return [
            'index' => ListCurrencies::route('/'),
            'create' => CreateCurrency::route('/create'),
            'view' => ViewCurrency::route('/{record}'),
            'edit' => EditCurrency::route('/{record}/edit'),
        ];
    }
}
