<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification
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
            ->subject('Booking Confirmed!')
            ->line('Your booking has been confirmed.')
            ->line('Teacher: ' . $this->booking->teacher->user->name)
            ->line('Date: ' . $this->booking->slot->slot_date)
            ->line('Time: ' . $this->booking->slot->start_time . ' - ' . $this->booking->slot->end_time)
            ->line('Zoom Link: ' . ($this->booking->teacher->zoom_link ?? 'Not available'))
            ->action('View Booking', url('/student/bookings/' . $this->booking->id))
            ->line('Thank you for using our platform!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Your booking with ' . $this->booking->teacher->user->name . ' has been confirmed.',
            'teacher' => $this->booking->teacher->user->name,
            'date' => $this->booking->slot->slot_date,
        ];
    }
}
