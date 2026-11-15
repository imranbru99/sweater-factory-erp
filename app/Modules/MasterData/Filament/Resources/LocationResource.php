<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Location;
use App\Modules\MasterData\Filament\Resources\LocationResource\Pages\CreateLocation;
use App\Modules\MasterData\Filament\Resources\LocationResource\Pages\EditLocation;
use App\Modules\MasterData\Filament\Resources\LocationResource\Pages\ListLocations;
use App\Modules\MasterData\Filament\Resources\LocationResource\Pages\ViewLocation;

class LocationResource extends MasterResource
{
    protected static ?string $model = Location::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Stores';

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'view' => ViewLocation::route('/{record}'),
            'edit' => EditLocation::route('/{record}/edit'),
        ];
    }
}
