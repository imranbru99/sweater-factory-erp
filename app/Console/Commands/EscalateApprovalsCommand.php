<?php

namespace App\Console\Commands;

use App\Modules\Core\Services\ApprovalEngine;
use Illuminate\Console\Command;

class EscalateApprovalsCommand extends Command
{
    protected $signature = 'approvals:escalate';

    protected $description = 'Escalate note sheets that have sat past their working-hour deadline';

    public function handle(ApprovalEngine $engine): int
    {
        $count = $engine->escalateDue();
        $this->info($count.' approval step'.($count === 1 ? '' : 's').' escalated.');

        return self::SUCCESS;
    }
}
