<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Season;
use App\Modules\MasterData\Filament\Resources\SeasonResource\Pages\CreateSeason;
use App\Modules\MasterData\Filament\Resources\SeasonResource\Pages\EditSeason;
use App\Modules\MasterData\Filament\Resources\SeasonResource\Pages\ListSeasons;
use App\Modules\MasterData\Filament\Resources\SeasonResource\Pages\ViewSeason;

class SeasonResource extends MasterResource
{
    protected static ?string $model = Season::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Style';

    public static function getPages(): array
    {
        return [
            'index' => ListSeasons::route('/'),
            'create' => CreateSeason::route('/create'),
            'view' => ViewSeason::route('/{record}'),
            'edit' => EditSeason::route('/{record}/edit'),
        ];
    }
}
