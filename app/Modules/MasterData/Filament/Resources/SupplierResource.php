<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Supplier;
use App\Modules\MasterData\Filament\Resources\SupplierResource\Pages\CreateSupplier;
use App\Modules\MasterData\Filament\Resources\SupplierResource\Pages\EditSupplier;
use App\Modules\MasterData\Filament\Resources\SupplierResource\Pages\ListSuppliers;
use App\Modules\MasterData\Filament\Resources\SupplierResource\Pages\ViewSupplier;

class SupplierResource extends MasterResource
{
    protected static ?string $model = Supplier::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Parties';

    public static function getRelations(): array
    {
        return [
            \App\Modules\MasterData\Filament\Resources\SupplierResource\RelationManagers\ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuppliers::route('/'),
            'create' => CreateSupplier::route('/create'),
            'view' => ViewSupplier::route('/{record}'),
            'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }
}
