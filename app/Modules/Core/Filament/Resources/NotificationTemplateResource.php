<?php

namespace App\Modules\Core\Filament\Resources;

use App\Modules\Core\Filament\Resources\NotificationTemplateResource\Pages\CreateNotificationTemplate;
use App\Modules\Core\Filament\Resources\NotificationTemplateResource\Pages\EditNotificationTemplate;
use App\Modules\Core\Filament\Resources\NotificationTemplateResource\Pages\ListNotificationTemplates;
use App\Modules\Core\Filament\Resources\NotificationTemplateResource\Pages\ViewNotificationTemplate;
use App\Modules\Core\Models\NotificationTemplate;
use App\Modules\MasterData\Filament\Support\MasterResource;

class NotificationTemplateResource extends MasterResource
{
    protected static ?string $model = NotificationTemplate::class;

    protected static string $moduleKey = 'core';

    protected static bool $supportsSoftDeletes = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    public static function getPages(): array
    {
        return [
            'index' => ListNotificationTemplates::route('/'),
            'create' => CreateNotificationTemplate::route('/create'),
            'view' => ViewNotificationTemplate::route('/{record}'),
            'edit' => EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
