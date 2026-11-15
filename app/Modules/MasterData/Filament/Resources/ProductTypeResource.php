<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\ProductType;
use App\Modules\MasterData\Filament\Resources\ProductTypeResource\Pages\CreateProductType;
use App\Modules\MasterData\Filament\Resources\ProductTypeResource\Pages\EditProductType;
use App\Modules\MasterData\Filament\Resources\ProductTypeResource\Pages\ListProductTypes;
use App\Modules\MasterData\Filament\Resources\ProductTypeResource\Pages\ViewProductType;

class ProductTypeResource extends MasterResource
{
    protected static ?string $model = ProductType::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Style';

    public static function getPages(): array
    {
        return [
            'index' => ListProductTypes::route('/'),
            'create' => CreateProductType::route('/create'),
            'view' => ViewProductType::route('/{record}'),
            'edit' => EditProductType::route('/{record}/edit'),
        ];
    }
}
