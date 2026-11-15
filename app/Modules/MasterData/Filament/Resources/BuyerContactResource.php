<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\BuyerContact;
use App\Modules\MasterData\Filament\Resources\BuyerContactResource\Pages\CreateBuyerContact;
use App\Modules\MasterData\Filament\Resources\BuyerContactResource\Pages\EditBuyerContact;
use App\Modules\MasterData\Filament\Resources\BuyerContactResource\Pages\ListBuyerContacts;
use App\Modules\MasterData\Filament\Resources\BuyerContactResource\Pages\ViewBuyerContact;

class BuyerContactResource extends MasterResource
{
    protected static ?string $model = BuyerContact::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Parties';

    public static function getPages(): array
    {
        return [
            'index' => ListBuyerContacts::route('/'),
            'create' => CreateBuyerContact::route('/create'),
            'view' => ViewBuyerContact::route('/{record}'),
            'edit' => EditBuyerContact::route('/{record}/edit'),
        ];
    }
}
