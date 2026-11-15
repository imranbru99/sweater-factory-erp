<?php

namespace App\Modules\Core\Notifications\Channels;

use App\Modules\Core\Contracts\WhatsAppDriver;
use App\Modules\Core\Models\NotificationLog;
use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    public function __construct(private readonly WhatsAppDriver $driver) {}

    public function send(object $notifiable, Notification $notification): void
    {
        $to = $notifiable->phone ?? null;
        if (! is_string($to) || $to === '') {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);
        $this->driver->send($to, $message);

        NotificationLog::query()->create([
            'notifiable_type' => $notifiable->getMorphClass(),
            'notifiable_id' => $notifiable->getKey(),
            'channel' => 'whatsapp',
            'template_key' => 'approval',
            'status' => 'sent',
            'payload' => $message,
            'sent_at' => now(),
        ]);
    }
}
