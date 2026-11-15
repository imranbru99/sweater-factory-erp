<?php

namespace App\Modules\Core\Filament\Resources\AuditResource\Pages;

use App\Modules\Core\Filament\Resources\AuditResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAudit extends CreateRecord
{
    protected static string $resource = AuditResource::class;
}
