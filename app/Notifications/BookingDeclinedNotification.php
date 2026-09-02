<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingDeclinedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Declined')
            ->line('Unfortunately, your booking has been declined.')
            ->line('Teacher: ' . $this->booking->teacher->user->name)
            ->line('Date: ' . $this->booking->slot->slot_date)
            ->action('Browse Teachers', url('/student/teachers'))
            ->line('You can try booking another time slot.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Your booking with ' . $this->booking->teacher->user->name . ' has been declined.',
        ];
    }
}
