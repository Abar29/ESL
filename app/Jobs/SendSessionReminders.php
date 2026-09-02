<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Notifications\SessionReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSessionReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $upcomingBookings = Booking::where('status', 'confirmed')
            ->whereHas('slot', function ($query) {
                $query->where('slot_date', now()->toDateString())
                    ->where('start_time', '<=', now()->addMinutes(15)->format('H:i:s'))
                    ->where('start_time', '>', now()->format('H:i:s'));
            })
            ->with(['teacher.user', 'slot'])
            ->get();

        foreach ($upcomingBookings as $booking) {
            $booking->student->notify(new SessionReminderNotification($booking));
        }
    }
}
