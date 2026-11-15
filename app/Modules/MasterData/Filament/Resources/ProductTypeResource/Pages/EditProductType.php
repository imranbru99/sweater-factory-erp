<?php

namespace App\Modules\MasterData\Filament\Resources\ProductTypeResource\Pages;

use App\Modules\MasterData\Filament\Resources\ProductTypeResource;
use Filament\Resources\Pages\EditRecord;

class EditProductType extends EditRecord
{
    protected static string $resource = ProductTypeResource::class;
}
