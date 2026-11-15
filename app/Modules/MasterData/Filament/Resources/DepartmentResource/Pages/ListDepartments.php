<?php

namespace App\Modules\MasterData\Filament\Resources\DepartmentResource\Pages;

use App\Modules\MasterData\Filament\Resources\DepartmentResource;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;
}
