<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionReminderNotification extends Notification
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
            ->subject('Session Starting Soon!')
            ->line('Your ESL session is starting in 10 minutes.')
            ->line('Teacher: ' . $this->booking->teacher->user->name)
            ->line('Time: ' . $this->booking->slot->start_time . ' - ' . $this->booking->slot->end_time)
            ->action('Join Meeting', $this->booking->teacher->zoom_link)
            ->line('Make sure to join on time!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Your session with ' . $this->booking->teacher->user->name . ' is starting in 10 minutes!',
            'zoom_link' => $this->booking->teacher->zoom_link,
        ];
    }
}
