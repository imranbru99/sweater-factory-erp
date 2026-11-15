<?php

namespace App\Console\Commands;

use App\Modules\Core\Models\Audit;
use Illuminate\Console\Command;

class PruneAuditsCommand extends Command
{
    protected $signature = 'factory:prune-audits';

    protected $description = 'Delete audit rows older than the retention window';

    public function handle(): int
    {
        $days = (int) config('factory.audit.retention_days', 365);
        $cutoff = now()->subDays($days);
        $deleted = Audit::query()->where('created_at', '<', $cutoff)->delete();
        $this->info($deleted.' audit rows removed.');

        return self::SUCCESS;
    }
}
