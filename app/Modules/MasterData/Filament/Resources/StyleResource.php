<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Style;
use App\Modules\MasterData\Filament\Resources\StyleResource\Pages\CreateStyle;
use App\Modules\MasterData\Filament\Resources\StyleResource\Pages\EditStyle;
use App\Modules\MasterData\Filament\Resources\StyleResource\Pages\ListStyles;
use App\Modules\MasterData\Filament\Resources\StyleResource\Pages\ViewStyle;

class StyleResource extends MasterResource
{
    protected static ?string $model = Style::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Style';

    public static function getPages(): array
    {
        return [
            'index' => ListStyles::route('/'),
            'create' => CreateStyle::route('/create'),
            'view' => ViewStyle::route('/{record}'),
            'edit' => EditStyle::route('/{record}/edit'),
        ];
    }
}
