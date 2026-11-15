<?php

namespace App\Modules\Core\Notifications;

use App\Modules\Core\Models\ApprovalRequest;
use App\Modules\Core\Notifications\Channels\SmsChannel;
use App\Modules\Core\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalRequested extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ApprovalRequest $request,
        public bool $escalated = false,
    ) {
        $this->onQueue('notifications');
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (filled($notifiable->email ?? null)) {
            $channels[] = 'mail';
        }

        if (filled($notifiable->phone ?? null) && $notifiable->wantsChannel('sms')) {
            $channels[] = SmsChannel::class;
        }

        if (filled($notifiable->phone ?? null) && $notifiable->wantsChannel('whatsapp')) {
            $channels[] = WhatsAppChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verb = $this->escalated ? 'escalated to you' : 'waiting for you';

        return (new MailMessage)
            ->subject('Approval '.$verb.': '.$this->request->document_type)
            ->line('Document '.$this->request->document_type.' is '.$verb.' at step '.$this->request->current_step.'.')
            ->line('Open the note sheet in the factory panel and approve or reject it.');
    }

    public function toSms(object $notifiable): string
    {
        $flag = $this->escalated ? 'ESCALATED' : 'Approval';

        return $flag.' '.$this->request->document_type.' step '.$this->request->current_step;
    }

    public function toWhatsApp(object $notifiable): string
    {
        return $this->toSms($notifiable);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'approval_request_id' => $this->request->id,
            'document_type' => $this->request->document_type,
            'step' => $this->request->current_step,
            'escalated' => $this->escalated,
        ];
    }
}
