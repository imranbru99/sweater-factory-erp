<?php

namespace App\Providers\Filament;

use App\Modules\Core\Filament\PanelStack;
use App\Modules\Portals\Filament\Account;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class SubcontractorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('subcontractor')
            ->path('subcontractor')
            ->login()
            ->brandName((string) config('factory.company.name'))
            ->colors(['primary' => Color::Amber])
            ->pages([Dashboard::class, Account::class])
            ->middleware(PanelStack::web())
            ->authMiddleware(PanelStack::auth());
    }
}
