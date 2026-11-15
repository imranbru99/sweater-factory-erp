<?php

namespace App\Modules\MasterData\Filament\Resources\ProductTypeResource\Pages;

use App\Modules\MasterData\Filament\Resources\ProductTypeResource;
use Filament\Resources\Pages\ListRecords;

class ListProductTypes extends ListRecords
{
    protected static string $resource = ProductTypeResource::class;
}
