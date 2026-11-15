<?php

namespace App\Modules\Core\Filament\Resources\InternalMemoResource\Pages;

use App\Modules\Core\Filament\Resources\InternalMemoResource;
use Filament\Resources\Pages\ListRecords;

class ListInternalMemos extends ListRecords
{
    protected static string $resource = InternalMemoResource::class;
}
