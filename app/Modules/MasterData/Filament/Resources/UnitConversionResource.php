<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\UnitConversion;
use App\Modules\MasterData\Filament\Resources\UnitConversionResource\Pages\CreateUnitConversion;
use App\Modules\MasterData\Filament\Resources\UnitConversionResource\Pages\EditUnitConversion;
use App\Modules\MasterData\Filament\Resources\UnitConversionResource\Pages\ListUnitConversions;
use App\Modules\MasterData\Filament\Resources\UnitConversionResource\Pages\ViewUnitConversion;

class UnitConversionResource extends MasterResource
{
    protected static ?string $model = UnitConversion::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    public static function getPages(): array
    {
        return [
            'index' => ListUnitConversions::route('/'),
            'create' => CreateUnitConversion::route('/create'),
            'view' => ViewUnitConversion::route('/{record}'),
            'edit' => EditUnitConversion::route('/{record}/edit'),
        ];
    }
}
