<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementNotSubmittedNotification extends Notification
{
    use Queueable;

    protected string $date;
    /**
     * Create a new notification instance.
     */
    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Settlement Pending',
            'message' =>
                'Settlement for ' . $this->date .
                ' has not been submitted yet.',
            'settlement_date' => $this->date,
        ];
    }
}
