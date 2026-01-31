<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryRequestRejectedNotification extends Notification
{
    use Queueable;

    protected Delivery $delivery;
    protected string $reason;

    /**
     * @param Delivery $delivery
     * @param string   $reason
     */
    public function __construct(Delivery $delivery, string $reason)
    {
        $this->delivery = $delivery;
        $this->reason = $reason;
    }

    /**
     * Notification channels
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Data stored in notifications table
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'delivery_request_rejected',
            'delivery_id' => $this->delivery->id,
            'invoice_no' => $this->delivery->invoice_no,

            'title' => 'Request Rejected',
            'message' => $this->buildMessage(),

            'reason' => $this->reason,
            'current_status' => $this->delivery->status,
            'customer_name' => optional($this->delivery->customer)->name,
        ];
    }

    /**
     * Human-friendly message
     */
    private function buildMessage(): string
    {
        if ($this->delivery->status === 'cancel_requested') {
            return sprintf(
                'Cancel request for invoice %s was rejected. Reason: %s',
                $this->delivery->invoice_no,
                $this->reason
            );
        }

        if ($this->delivery->status === 'reschedule_requested') {
            return sprintf(
                'Reschedule request for invoice %s was rejected. Reason: %s',
                $this->delivery->invoice_no,
                $this->reason
            );
        }

        return sprintf(
            'Request for invoice %s was rejected. Reason: %s',
            $this->delivery->invoice_no,
            $this->reason
        );
    }
}
