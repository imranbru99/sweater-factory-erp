<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('approvals:escalate')->everyFifteenMinutes();
Schedule::command('factory:prune-audits')->daily();
