<?php

namespace App\Modules\Core\Filament\Resources\AuditResource\Pages;

use App\Modules\Core\Filament\Resources\AuditResource;
use Filament\Resources\Pages\ListRecords;

class ListAudits extends ListRecords
{
    protected static string $resource = AuditResource::class;
}
