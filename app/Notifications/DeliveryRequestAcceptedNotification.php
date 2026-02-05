<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryRequestAcceptedNotification extends Notification
{
    use Queueable;

    protected Delivery $delivery;

    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * Delivery channels
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Data saved in notifications table
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'delivery_request_approved',
            'delivery_id' => $this->delivery->id,
            'invoice_no' => $this->delivery->invoice_no,
            'status' => $this->delivery->status,

            'title' => 'Request Approved',
            'message' => $this->buildMessage(),

            'customer_name' => optional($this->delivery->customer)->name,
        ];
    }

    /**
     * Human-friendly message
     */
    private function buildMessage(): string
    {
        if ($this->delivery->status === 'cancelled') {
            return sprintf(
                'Cancel request for invoice %s has been approved.',
                $this->delivery->invoice_no
            );
        }

        if ($this->delivery->status === 'pending') {
            return sprintf(
                'Reschedule request for invoice %s has been approved. Delivery moved back to pending.',
                $this->delivery->invoice_no
            );
        }

        return sprintf(
            'Request for invoice %s has been approved.',
            $this->delivery->invoice_no
        );
    }
}
