<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Ply;
use App\Modules\MasterData\Filament\Resources\PlyResource\Pages\CreatePly;
use App\Modules\MasterData\Filament\Resources\PlyResource\Pages\EditPly;
use App\Modules\MasterData\Filament\Resources\PlyResource\Pages\ListPlys;
use App\Modules\MasterData\Filament\Resources\PlyResource\Pages\ViewPly;

class PlyResource extends MasterResource
{
    protected static ?string $model = Ply::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Yarn & Knit';

    public static function getPages(): array
    {
        return [
            'index' => ListPlys::route('/'),
            'create' => CreatePly::route('/create'),
            'view' => ViewPly::route('/{record}'),
            'edit' => EditPly::route('/{record}/edit'),
        ];
    }
}
