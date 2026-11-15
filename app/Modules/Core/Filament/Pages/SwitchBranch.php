<?php

namespace App\Modules\Core\Filament\Pages;

use App\Models\User;
use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Scopes\BranchScope;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SwitchBranch extends Page
{
    protected static ?string $navigationLabel = 'Working branch';

    protected static ?string $title = 'Working branch';

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    protected static ?string $slug = 'branch';

    protected string $view = 'filament.switch-branch';

    public ?int $branchId = null;

    public static function canAccess(): bool
    {
        return auth()->user() instanceof User;
    }

    public function mount(): void
    {
        $user = auth()->user();
        $this->branchId = $user instanceof User ? $user->currentBranchId() : null;
    }

    public function save(): void
    {
        $user = auth()->user();
        if (! $user instanceof User || $this->branchId === null) {
            return;
        }

        if (! in_array($this->branchId, $user->accessibleBranchIds(), true)) {
            Notification::make()->title('That unit is outside your access')->danger()->send();

            return;
        }

        session(['branch_id' => $this->branchId]);
        Notification::make()->title('Working branch updated')->success()->send();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $user = auth()->user();
        $ids = $user instanceof User ? $user->accessibleBranchIds() : [];

        return [
            'branches' => Branch::query()->withoutGlobalScope(BranchScope::class)->whereIn('id', $ids)->orderBy('code')->get(),
        ];
    }
}
