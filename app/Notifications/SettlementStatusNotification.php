<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $settlement,
        public string $status // verified | rejected
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'settlement_status',
            'status' => $this->status,
            'settlement_id' => $this->settlement->id,

            'title' => $this->status === 'approved'
                ? 'Settlement Approved'
                : 'Settlement Rejected',

            'message' => $this->buildMessage(),

            'settlement_date' => $this->settlement->settlement_date,
            'amount' => $this->settlement->total_amount,
            'reject_reason' => $this->settlement->reject_reason,
        ];
    }

    private function buildMessage(): string
    {
        if ($this->status === 'approved') {
            return sprintf(
                'Your settlement of ₹%s on %s has been approved.',
                number_format($this->settlement->total_amount, 2),
                Carbon::parse($this->settlement->settlement_date)->format('d-m-Y')
            );
        }

        return sprintf(
            'Your settlement on %s was rejected. Reason: %s',
            $this->settlement->settlement_date,
            $this->settlement->reject_reason ?? 'Not specified'
        );
    }
}
