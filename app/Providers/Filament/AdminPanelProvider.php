<?php

namespace App\Providers\Filament;

use App\Modules\Core\Filament\PanelStack;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName((string) config('factory.company.name'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Modules/Core/Filament/Resources'), for: 'App\\Modules\\Core\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/MasterData/Filament/Resources'), for: 'App\\Modules\\MasterData\\Filament\\Resources')
            ->discoverPages(in: app_path('Modules/Core/Filament/Pages'), for: 'App\\Modules\\Core\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Modules/Core/Filament/Widgets'), for: 'App\\Modules\\Core\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware(PanelStack::web())
            ->authMiddleware(PanelStack::auth())
            ->multiFactorAuthentication([
                AppAuthentication::make()->recoverable(),
            ], isRequired: fn (): bool => auth()->user()?->requiresTwoFactor() ?? false);
    }
}
