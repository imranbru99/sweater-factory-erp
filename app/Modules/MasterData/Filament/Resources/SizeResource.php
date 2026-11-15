<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Size;
use App\Modules\MasterData\Filament\Resources\SizeResource\Pages\CreateSize;
use App\Modules\MasterData\Filament\Resources\SizeResource\Pages\EditSize;
use App\Modules\MasterData\Filament\Resources\SizeResource\Pages\ListSizes;
use App\Modules\MasterData\Filament\Resources\SizeResource\Pages\ViewSize;

class SizeResource extends MasterResource
{
    protected static ?string $model = Size::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Style';

    public static function getPages(): array
    {
        return [
            'index' => ListSizes::route('/'),
            'create' => CreateSize::route('/create'),
            'view' => ViewSize::route('/{record}'),
            'edit' => EditSize::route('/{record}/edit'),
        ];
    }
}
