<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\StitchType;
use App\Modules\MasterData\Filament\Resources\StitchTypeResource\Pages\CreateStitchType;
use App\Modules\MasterData\Filament\Resources\StitchTypeResource\Pages\EditStitchType;
use App\Modules\MasterData\Filament\Resources\StitchTypeResource\Pages\ListStitchTypes;
use App\Modules\MasterData\Filament\Resources\StitchTypeResource\Pages\ViewStitchType;

class StitchTypeResource extends MasterResource
{
    protected static ?string $model = StitchType::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Yarn & Knit';

    public static function getPages(): array
    {
        return [
            'index' => ListStitchTypes::route('/'),
            'create' => CreateStitchType::route('/create'),
            'view' => ViewStitchType::route('/{record}'),
            'edit' => EditStitchType::route('/{record}/edit'),
        ];
    }
}
