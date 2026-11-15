<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Holiday;
use App\Modules\MasterData\Filament\Resources\HolidayResource\Pages\CreateHoliday;
use App\Modules\MasterData\Filament\Resources\HolidayResource\Pages\EditHoliday;
use App\Modules\MasterData\Filament\Resources\HolidayResource\Pages\ListHolidays;
use App\Modules\MasterData\Filament\Resources\HolidayResource\Pages\ViewHoliday;

class HolidayResource extends MasterResource
{
    protected static ?string $model = Holiday::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Organisation';

    public static function getPages(): array
    {
        return [
            'index' => ListHolidays::route('/'),
            'create' => CreateHoliday::route('/create'),
            'view' => ViewHoliday::route('/{record}'),
            'edit' => EditHoliday::route('/{record}/edit'),
        ];
    }
}
