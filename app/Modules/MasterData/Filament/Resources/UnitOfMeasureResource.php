<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\UnitOfMeasure;
use App\Modules\MasterData\Filament\Resources\UnitOfMeasureResource\Pages\CreateUnitOfMeasure;
use App\Modules\MasterData\Filament\Resources\UnitOfMeasureResource\Pages\EditUnitOfMeasure;
use App\Modules\MasterData\Filament\Resources\UnitOfMeasureResource\Pages\ListUnitOfMeasures;
use App\Modules\MasterData\Filament\Resources\UnitOfMeasureResource\Pages\ViewUnitOfMeasure;

class UnitOfMeasureResource extends MasterResource
{
    protected static ?string $model = UnitOfMeasure::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Master data';

    public static function getPages(): array
    {
        return [
            'index' => ListUnitOfMeasures::route('/'),
            'create' => CreateUnitOfMeasure::route('/create'),
            'view' => ViewUnitOfMeasure::route('/{record}'),
            'edit' => EditUnitOfMeasure::route('/{record}/edit'),
        ];
    }
}
