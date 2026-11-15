<?php

namespace App\Modules\Core\Contracts;

interface SmsDriver
{
    public function send(string $to, string $message): void;
}
