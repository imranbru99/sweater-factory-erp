<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\HsCode;
use App\Modules\MasterData\Filament\Resources\HsCodeResource\Pages\CreateHsCode;
use App\Modules\MasterData\Filament\Resources\HsCodeResource\Pages\EditHsCode;
use App\Modules\MasterData\Filament\Resources\HsCodeResource\Pages\ListHsCodes;
use App\Modules\MasterData\Filament\Resources\HsCodeResource\Pages\ViewHsCode;

class HsCodeResource extends MasterResource
{
    protected static ?string $model = HsCode::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    public static function getPages(): array
    {
        return [
            'index' => ListHsCodes::route('/'),
            'create' => CreateHsCode::route('/create'),
            'view' => ViewHsCode::route('/{record}'),
            'edit' => EditHsCode::route('/{record}/edit'),
        ];
    }
}
