<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $payment,
        public string $status // verified | rejected
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Settlement ' . ucfirst($this->status),
            'message' =>
                "Settlement ({$this->settlement->from_date}
                 → {$this->settlement->to_date}) was {$this->status}",
            'settlement_id' => $this->settlement->id,
            'status' => $this->status,
        ];
    }
}
