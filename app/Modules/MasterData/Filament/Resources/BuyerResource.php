<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Buyer;
use App\Modules\MasterData\Filament\Resources\BuyerResource\Pages\CreateBuyer;
use App\Modules\MasterData\Filament\Resources\BuyerResource\Pages\EditBuyer;
use App\Modules\MasterData\Filament\Resources\BuyerResource\Pages\ListBuyers;
use App\Modules\MasterData\Filament\Resources\BuyerResource\Pages\ViewBuyer;

class BuyerResource extends MasterResource
{
    protected static ?string $model = Buyer::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Parties';

    public static function getRelations(): array
    {
        return [
            \App\Modules\MasterData\Filament\Resources\BuyerResource\RelationManagers\ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBuyers::route('/'),
            'create' => CreateBuyer::route('/create'),
            'view' => ViewBuyer::route('/{record}'),
            'edit' => EditBuyer::route('/{record}/edit'),
        ];
    }
}
