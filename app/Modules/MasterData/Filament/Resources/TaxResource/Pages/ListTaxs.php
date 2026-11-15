<?php

namespace App\Modules\MasterData\Filament\Resources\TaxResource\Pages;

use App\Modules\MasterData\Filament\Resources\TaxResource;
use Filament\Resources\Pages\ListRecords;

class ListTaxs extends ListRecords
{
    protected static string $resource = TaxResource::class;
}
