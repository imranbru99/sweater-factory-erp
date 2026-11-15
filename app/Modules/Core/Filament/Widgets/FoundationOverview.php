<?php

namespace App\Modules\Core\Filament\Widgets;

use App\Modules\Core\Enums\ApprovalStatus;
use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\InternalMemo;
use App\Modules\MasterData\Models\Buyer;
use App\Modules\MasterData\Models\Machine;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FoundationOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Units', (string) Branch::query()->count()),
            Stat::make('Buyers', (string) Buyer::query()->count()),
            Stat::make('Machines', (string) Machine::query()->count()),
            Stat::make('Notes waiting', (string) InternalMemo::query()->where('status', ApprovalStatus::Pending)->count()),
        ];
    }
}
