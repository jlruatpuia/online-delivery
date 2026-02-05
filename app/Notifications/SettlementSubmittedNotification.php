<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementSubmittedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $settlement) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $date = Carbon::parse($this->settlement->settlement_date)
            ->format('d-m-Y');
        return [
            'title' => 'Settlement Submitted',
            'message' =>
                "Settlement submitted by {$this->settlement->deliveryBoy->name} ({$date})",
            'settlement_id' => $this->settlement->id,
        ];
    }
}
