<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Department;
use App\Modules\MasterData\Filament\Resources\DepartmentResource\Pages\CreateDepartment;
use App\Modules\MasterData\Filament\Resources\DepartmentResource\Pages\EditDepartment;
use App\Modules\MasterData\Filament\Resources\DepartmentResource\Pages\ListDepartments;
use App\Modules\MasterData\Filament\Resources\DepartmentResource\Pages\ViewDepartment;

class DepartmentResource extends MasterResource
{
    protected static ?string $model = Department::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Style';

    public static function getPages(): array
    {
        return [
            'index' => ListDepartments::route('/'),
            'create' => CreateDepartment::route('/create'),
            'view' => ViewDepartment::route('/{record}'),
            'edit' => EditDepartment::route('/{record}/edit'),
        ];
    }
}
