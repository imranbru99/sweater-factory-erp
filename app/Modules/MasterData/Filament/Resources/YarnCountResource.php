<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\YarnCount;
use App\Modules\MasterData\Filament\Resources\YarnCountResource\Pages\CreateYarnCount;
use App\Modules\MasterData\Filament\Resources\YarnCountResource\Pages\EditYarnCount;
use App\Modules\MasterData\Filament\Resources\YarnCountResource\Pages\ListYarnCounts;
use App\Modules\MasterData\Filament\Resources\YarnCountResource\Pages\ViewYarnCount;

class YarnCountResource extends MasterResource
{
    protected static ?string $model = YarnCount::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Yarn & Knit';

    public static function getPages(): array
    {
        return [
            'index' => ListYarnCounts::route('/'),
            'create' => CreateYarnCount::route('/create'),
            'view' => ViewYarnCount::route('/{record}'),
            'edit' => EditYarnCount::route('/{record}/edit'),
        ];
    }
}
