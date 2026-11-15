<?php

namespace App\Modules\Core\Notifications\Drivers;

use App\Modules\Core\Contracts\SmsDriver;
use Illuminate\Support\Facades\Log;

class LogSmsDriver implements SmsDriver
{
    public function send(string $to, string $message): void
    {
        Log::info('SMS', ['to' => $to, 'message' => $message]);
    }
}
