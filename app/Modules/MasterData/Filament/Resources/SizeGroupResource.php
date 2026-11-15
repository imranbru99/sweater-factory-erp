<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\SizeGroup;
use App\Modules\MasterData\Filament\Resources\SizeGroupResource\Pages\CreateSizeGroup;
use App\Modules\MasterData\Filament\Resources\SizeGroupResource\Pages\EditSizeGroup;
use App\Modules\MasterData\Filament\Resources\SizeGroupResource\Pages\ListSizeGroups;
use App\Modules\MasterData\Filament\Resources\SizeGroupResource\Pages\ViewSizeGroup;

class SizeGroupResource extends MasterResource
{
    protected static ?string $model = SizeGroup::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Style';

    public static function getRelations(): array
    {
        return [
            \App\Modules\MasterData\Filament\Resources\SizeGroupResource\RelationManagers\SizesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSizeGroups::route('/'),
            'create' => CreateSizeGroup::route('/create'),
            'view' => ViewSizeGroup::route('/{record}'),
            'edit' => EditSizeGroup::route('/{record}/edit'),
        ];
    }
}
