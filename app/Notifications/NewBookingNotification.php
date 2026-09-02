<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
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
            ->subject('New Booking Request')
            ->line('You have a new booking request!')
            ->line('Student: ' . $this->booking->student->name)
            ->line('Date: ' . $this->booking->slot->slot_date)
            ->line('Time: ' . $this->booking->slot->start_time . ' - ' . $this->booking->slot->end_time)
            ->line('Payment Reference: ' . $this->booking->payment_reference)
            ->action('Review Booking', url('/teacher/bookings/' . $this->booking->id))
            ->line('Please verify the payment and accept or decline.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'New booking from ' . $this->booking->student->name . ' on ' . $this->booking->slot->slot_date,
            'student' => $this->booking->student->name,
        ];
    }
}
