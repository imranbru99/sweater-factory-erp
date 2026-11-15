<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Tax;
use App\Modules\MasterData\Filament\Resources\TaxResource\Pages\CreateTax;
use App\Modules\MasterData\Filament\Resources\TaxResource\Pages\EditTax;
use App\Modules\MasterData\Filament\Resources\TaxResource\Pages\ListTaxs;
use App\Modules\MasterData\Filament\Resources\TaxResource\Pages\ViewTax;

class TaxResource extends MasterResource
{
    protected static ?string $model = Tax::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    public static function getPages(): array
    {
        return [
            'index' => ListTaxs::route('/'),
            'create' => CreateTax::route('/create'),
            'view' => ViewTax::route('/{record}'),
            'edit' => EditTax::route('/{record}/edit'),
        ];
    }
}
