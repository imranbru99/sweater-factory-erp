<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Subcontractor;
use App\Modules\MasterData\Filament\Resources\SubcontractorResource\Pages\CreateSubcontractor;
use App\Modules\MasterData\Filament\Resources\SubcontractorResource\Pages\EditSubcontractor;
use App\Modules\MasterData\Filament\Resources\SubcontractorResource\Pages\ListSubcontractors;
use App\Modules\MasterData\Filament\Resources\SubcontractorResource\Pages\ViewSubcontractor;

class SubcontractorResource extends MasterResource
{
    protected static ?string $model = Subcontractor::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Parties';

    public static function getPages(): array
    {
        return [
            'index' => ListSubcontractors::route('/'),
            'create' => CreateSubcontractor::route('/create'),
            'view' => ViewSubcontractor::route('/{record}'),
            'edit' => EditSubcontractor::route('/{record}/edit'),
        ];
    }
}
