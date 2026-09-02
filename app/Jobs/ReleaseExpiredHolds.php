<?php

namespace App\Jobs;

use App\Models\AvailabilitySlot;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleaseExpiredHolds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiredBookings = Booking::where('status', 'pending_payment')
            ->where('held_until', '<=', now())
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'cancelled']);
            $booking->slot->update(['status' => 'available']);
        }

        // Also release held slots with no booking
        AvailabilitySlot::where('status', 'held')
            ->where('updated_at', '<=', now()->subMinutes(30))
            ->update(['status' => 'available']);
    }
}
