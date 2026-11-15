<?php

namespace App\Modules\Core\Filament\Resources;

use App\Modules\Core\Filament\Resources\BranchResource\Pages\CreateBranch;
use App\Modules\Core\Filament\Resources\BranchResource\Pages\EditBranch;
use App\Modules\Core\Filament\Resources\BranchResource\Pages\ListBranchs;
use App\Modules\Core\Filament\Resources\BranchResource\Pages\ViewBranch;
use App\Modules\Core\Models\Branch;
use App\Modules\MasterData\Filament\Support\MasterResource;

class BranchResource extends MasterResource
{
    protected static ?string $model = Branch::class;

    protected static string $moduleKey = 'core';

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    public static function getPages(): array
    {
        return [
            'index' => ListBranchs::route('/'),
            'create' => CreateBranch::route('/create'),
            'view' => ViewBranch::route('/{record}'),
            'edit' => EditBranch::route('/{record}/edit'),
        ];
    }
}
