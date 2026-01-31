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
//        return [
//            'title' => 'Settlement ' . ucfirst($this->status),
//            'message' =>
//                "Settlement ({$this->settlement->from_date}
//                 → {$this->settlement->to_date}) was {$this->status}",
//            'settlement_id' => $this->settlement->id,
//            'status' => $this->status,
//        ];
        return [
            'type' => 'settlement_status',
            'status' => $this->status,
            'settlement_id' => $this->settlement->id,

            'title' => $this->status === 'approved'
                ? 'Settlement Approved'
                : 'Settlement Rejected',

            'message' => $this->buildMessage(),

            'from_date' => $this->settlement->from_date,
            'to_date' => $this->settlement->to_date,
            'amount' => $this->settlement->total_amount,
            'reject_reason' => $this->settlement->reject_reason,
        ];
    }

    private function buildMessage(): string
    {
        if ($this->status === 'approved') {
            return sprintf(
                'Your settlement from %s to %s for ₹%s has been approved.',
                $this->settlement->from_date,
                $this->settlement->to_date,
                number_format($this->settlement->total_amount, 2)
            );
        }

        return sprintf(
            'Your settlement from %s to %s was rejected. Reason: %s',
            $this->settlement->from_date,
            $this->settlement->to_date,
            $this->settlement->reject_reason ?? 'Not specified'
        );
    }
}
