<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $delivery,
        public string $type,   // cancel | reschedule
        public string $reason
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Delivery ' . ucfirst($this->type) . ' Request',
            'message' => "Invoice #{$this->delivery->invoice_no} requested for {$this->type}",
            'delivery_id' => $this->delivery->id,
            'type' => $this->type,
            'reason' => $this->reason,
        ];
    }
}
