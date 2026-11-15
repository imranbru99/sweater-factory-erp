<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Shift;
use App\Modules\MasterData\Filament\Resources\ShiftResource\Pages\CreateShift;
use App\Modules\MasterData\Filament\Resources\ShiftResource\Pages\EditShift;
use App\Modules\MasterData\Filament\Resources\ShiftResource\Pages\ListShifts;
use App\Modules\MasterData\Filament\Resources\ShiftResource\Pages\ViewShift;

class ShiftResource extends MasterResource
{
    protected static ?string $model = Shift::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Organisation';

    public static function getPages(): array
    {
        return [
            'index' => ListShifts::route('/'),
            'create' => CreateShift::route('/create'),
            'view' => ViewShift::route('/{record}'),
            'edit' => EditShift::route('/{record}/edit'),
        ];
    }
}
