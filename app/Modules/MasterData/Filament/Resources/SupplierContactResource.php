<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\SupplierContact;
use App\Modules\MasterData\Filament\Resources\SupplierContactResource\Pages\CreateSupplierContact;
use App\Modules\MasterData\Filament\Resources\SupplierContactResource\Pages\EditSupplierContact;
use App\Modules\MasterData\Filament\Resources\SupplierContactResource\Pages\ListSupplierContacts;
use App\Modules\MasterData\Filament\Resources\SupplierContactResource\Pages\ViewSupplierContact;

class SupplierContactResource extends MasterResource
{
    protected static ?string $model = SupplierContact::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Parties';

    public static function getPages(): array
    {
        return [
            'index' => ListSupplierContacts::route('/'),
            'create' => CreateSupplierContact::route('/create'),
            'view' => ViewSupplierContact::route('/{record}'),
            'edit' => EditSupplierContact::route('/{record}/edit'),
        ];
    }
}
