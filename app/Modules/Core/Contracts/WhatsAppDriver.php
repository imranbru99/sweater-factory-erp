<?php

namespace App\Modules\Core\Contracts;

interface WhatsAppDriver
{
    public function send(string $to, string $message): void;
}
