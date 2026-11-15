<?php

namespace App\Modules\Core\Filament\Pages;

use App\Models\User;
use App\Modules\Core\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CompanySettings extends Page
{
    protected static ?string $navigationLabel = 'Company';

    protected static ?string $title = 'Company settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    protected static ?string $slug = 'company';

    protected string $view = 'filament.company-settings';

    public string $name = '';

    public string $nameBn = '';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && ($user->isSuperAdmin() || $user->hasPermission('core.settings.manage'));
    }

    public function mount(): void
    {
        $this->name = (string) Setting::getValue('company', 'name', config('factory.company.name'));
        $this->nameBn = (string) Setting::getValue('company', 'name_bn', config('factory.company.name_bn'));
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:160'],
            'nameBn' => ['required', 'string', 'max:160'],
        ]);

        Setting::putValue('company', 'name', $this->name);
        Setting::putValue('company', 'name_bn', $this->nameBn);

        Notification::make()->title('Company settings saved')->success()->send();
    }
}
