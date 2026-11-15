<?php

namespace App\Modules\Core\Filament\Resources\BranchResource\Pages;

use App\Modules\Core\Filament\Resources\BranchResource;
use Filament\Resources\Pages\ListRecords;

class ListBranchs extends ListRecords
{
    protected static string $resource = BranchResource::class;
}
