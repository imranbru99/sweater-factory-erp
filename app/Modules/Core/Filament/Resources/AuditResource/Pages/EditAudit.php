<?php

namespace App\Modules\Core\Filament\Resources\AuditResource\Pages;

use App\Modules\Core\Filament\Resources\AuditResource;
use Filament\Resources\Pages\EditRecord;

class EditAudit extends EditRecord
{
    protected static string $resource = AuditResource::class;
}
