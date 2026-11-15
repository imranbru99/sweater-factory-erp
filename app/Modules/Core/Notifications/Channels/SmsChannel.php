<?php

namespace App\Modules\Core\Notifications\Channels;

use App\Modules\Core\Contracts\SmsDriver;
use App\Modules\Core\Models\NotificationLog;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function __construct(private readonly SmsDriver $driver) {}

    public function send(object $notifiable, Notification $notification): void
    {
        $to = $notifiable->phone ?? null;
        if (! is_string($to) || $to === '') {
            return;
        }

        $message = $notification->toSms($notifiable);

        try {
            $this->driver->send($to, $message);
            $status = 'sent';
            $error = null;
        } catch (\Throwable $exception) {
            $status = 'failed';
            $error = $exception->getMessage();
        }

        NotificationLog::query()->create([
            'notifiable_type' => $notifiable->getMorphClass(),
            'notifiable_id' => $notifiable->getKey(),
            'channel' => 'sms',
            'template_key' => 'approval',
            'status' => $status,
            'payload' => $message,
            'error' => $error,
            'sent_at' => now(),
        ]);
    }
}
