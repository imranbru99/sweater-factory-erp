<?php

namespace App\Modules\Core\Filament\Pages;

use App\Models\User;
use App\Modules\Core\Exceptions\ModuleException;
use App\Modules\Core\Models\ModuleRecord;
use App\Modules\Core\Services\ModuleManager;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Modules extends Page
{
    protected static ?string $navigationLabel = 'Modules';

    protected static ?string $title = 'Modules';

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    protected static ?string $slug = 'modules';

    protected string $view = 'filament.modules';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && ($user->isSuperAdmin() || $user->hasPermission('core.module.toggle'));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'modules' => ModuleRecord::query()->orderBy('sort')->get(),
        ];
    }

    public function toggle(string $key): void
    {
        $module = ModuleRecord::query()->where('key', $key)->firstOrFail();

        try {
            app(ModuleManager::class)->setEnabled($key, ! $module->is_enabled);
            Notification::make()->title('Module updated')->success()->send();
        } catch (ModuleException $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();
        }
    }
}
