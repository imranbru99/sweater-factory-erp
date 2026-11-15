<?php

namespace App\Modules\Core\Filament\Resources;

use App\Modules\Core\Filament\Resources\RoleResource\Pages\CreateRole;
use App\Modules\Core\Filament\Resources\RoleResource\Pages\EditRole;
use App\Modules\Core\Filament\Resources\RoleResource\Pages\ListRoles;
use App\Modules\Core\Filament\Resources\RoleResource\Pages\ViewRole;
use App\Modules\Core\Models\Role;
use App\Modules\MasterData\Filament\Support\MasterResource;

class RoleResource extends MasterResource
{
    protected static ?string $model = Role::class;

    protected static string $moduleKey = 'core';

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
