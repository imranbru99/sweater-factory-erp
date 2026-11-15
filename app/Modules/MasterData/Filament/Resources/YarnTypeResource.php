<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\YarnType;
use App\Modules\MasterData\Filament\Resources\YarnTypeResource\Pages\CreateYarnType;
use App\Modules\MasterData\Filament\Resources\YarnTypeResource\Pages\EditYarnType;
use App\Modules\MasterData\Filament\Resources\YarnTypeResource\Pages\ListYarnTypes;
use App\Modules\MasterData\Filament\Resources\YarnTypeResource\Pages\ViewYarnType;

class YarnTypeResource extends MasterResource
{
    protected static ?string $model = YarnType::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Yarn & Knit';

    public static function getPages(): array
    {
        return [
            'index' => ListYarnTypes::route('/'),
            'create' => CreateYarnType::route('/create'),
            'view' => ViewYarnType::route('/{record}'),
            'edit' => EditYarnType::route('/{record}/edit'),
        ];
    }
}
