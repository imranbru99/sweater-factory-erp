<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\DefectCode;
use App\Modules\MasterData\Filament\Resources\DefectCodeResource\Pages\CreateDefectCode;
use App\Modules\MasterData\Filament\Resources\DefectCodeResource\Pages\EditDefectCode;
use App\Modules\MasterData\Filament\Resources\DefectCodeResource\Pages\ListDefectCodes;
use App\Modules\MasterData\Filament\Resources\DefectCodeResource\Pages\ViewDefectCode;

class DefectCodeResource extends MasterResource
{
    protected static ?string $model = DefectCode::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    public static function getPages(): array
    {
        return [
            'index' => ListDefectCodes::route('/'),
            'create' => CreateDefectCode::route('/create'),
            'view' => ViewDefectCode::route('/{record}'),
            'edit' => EditDefectCode::route('/{record}/edit'),
        ];
    }
}
