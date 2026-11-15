<?php

namespace App\Modules\Portals\Filament;

use Filament\Pages\Page;

class Account extends Page
{
    protected static ?string $navigationLabel = 'Account';

    protected static ?string $title = 'Account';

    protected static ?string $slug = 'account';

    protected string $view = 'filament.portal-account';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return ['account' => auth()->user()];
    }
}
