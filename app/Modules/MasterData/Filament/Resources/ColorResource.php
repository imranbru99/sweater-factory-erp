<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Color;
use App\Modules\MasterData\Filament\Resources\ColorResource\Pages\CreateColor;
use App\Modules\MasterData\Filament\Resources\ColorResource\Pages\EditColor;
use App\Modules\MasterData\Filament\Resources\ColorResource\Pages\ListColors;
use App\Modules\MasterData\Filament\Resources\ColorResource\Pages\ViewColor;

class ColorResource extends MasterResource
{
    protected static ?string $model = Color::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Style';

    public static function getPages(): array
    {
        return [
            'index' => ListColors::route('/'),
            'create' => CreateColor::route('/create'),
            'view' => ViewColor::route('/{record}'),
            'edit' => EditColor::route('/{record}/edit'),
        ];
    }
}
