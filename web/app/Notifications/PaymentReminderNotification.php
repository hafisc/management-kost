<?php

namespace App\Notifications;

use App\Models\Stay;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public Stay $stay) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Reminder: Pembayaran untuk kamar '.$this->stay->room->name.' sudah jatuh tempo.',
            'stay_id' => $this->stay->id,
            'room_name' => $this->stay->room->name,
            'tenant_name' => $this->stay->tenant->name,
        ];
    }
}
