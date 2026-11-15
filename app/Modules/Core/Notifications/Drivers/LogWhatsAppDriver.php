<?php

namespace App\Modules\Core\Notifications\Drivers;

use App\Modules\Core\Contracts\WhatsAppDriver;
use Illuminate\Support\Facades\Log;

class LogWhatsAppDriver implements WhatsAppDriver
{
    public function send(string $to, string $message): void
    {
        Log::info('WhatsApp', ['to' => $to, 'message' => $message]);
    }
}
