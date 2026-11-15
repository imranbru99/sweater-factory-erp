<?php

namespace App\Modules\Core\Filament\Resources;

use App\Modules\Core\Filament\Resources\AuditResource\Pages\CreateAudit;
use App\Modules\Core\Filament\Resources\AuditResource\Pages\EditAudit;
use App\Modules\Core\Filament\Resources\AuditResource\Pages\ListAudits;
use App\Modules\Core\Filament\Resources\AuditResource\Pages\ViewAudit;
use App\Modules\Core\Models\Audit;
use App\Modules\MasterData\Filament\Support\MasterResource;

class AuditResource extends MasterResource
{
    protected static ?string $model = Audit::class;

    protected static string $moduleKey = 'core';

    protected static bool $supportsSoftDeletes = false;

    public static function canCreate(): bool
    {
        return false;
    }

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    public static function getPages(): array
    {
        return [
            'index' => ListAudits::route('/'),
            'create' => CreateAudit::route('/create'),
            'view' => ViewAudit::route('/{record}'),
            'edit' => EditAudit::route('/{record}/edit'),
        ];
    }
}
