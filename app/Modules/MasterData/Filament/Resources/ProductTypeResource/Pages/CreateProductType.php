<?php

namespace App\Modules\MasterData\Filament\Resources\ProductTypeResource\Pages;

use App\Modules\MasterData\Filament\Resources\ProductTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductType extends CreateRecord
{
    protected static string $resource = ProductTypeResource::class;
}
