<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Warehouse;
use App\Modules\MasterData\Filament\Resources\WarehouseResource\Pages\CreateWarehouse;
use App\Modules\MasterData\Filament\Resources\WarehouseResource\Pages\EditWarehouse;
use App\Modules\MasterData\Filament\Resources\WarehouseResource\Pages\ListWarehouses;
use App\Modules\MasterData\Filament\Resources\WarehouseResource\Pages\ViewWarehouse;

class WarehouseResource extends MasterResource
{
    protected static ?string $model = Warehouse::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Stores';

    public static function getRelations(): array
    {
        return [
            \App\Modules\MasterData\Filament\Resources\WarehouseResource\RelationManagers\LocationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarehouses::route('/'),
            'create' => CreateWarehouse::route('/create'),
            'view' => ViewWarehouse::route('/{record}'),
            'edit' => EditWarehouse::route('/{record}/edit'),
        ];
    }
}
