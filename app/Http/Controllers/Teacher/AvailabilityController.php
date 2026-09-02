<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AvailabilitySlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AvailabilityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $slots = $user->teacherProfile->availabilitySlots()
            ->where('slot_date', '>=', now()->toDateString())
            ->orderBy('slot_date')
            ->orderBy('start_time')
            ->get();

        return Inertia::render('Teacher/Availability/Index', [
            'slots' => $slots,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slot_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $user = Auth::user();

        $overlapping = AvailabilitySlot::where('teacher_id', $user->teacherProfile->id)
            ->where('slot_date', $validated['slot_date'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })
            ->exists();

        if ($overlapping) {
            return back()->withErrors(['slot_date' => 'This time slot overlaps with an existing slot.']);
        }

        AvailabilitySlot::create([
            'teacher_id' => $user->teacherProfile->id,
            ...$validated,
            'status' => 'available',
        ]);

        return back()->with('success', 'Availability slot created.');
    }

    public function update(Request $request, AvailabilitySlot $slot)
    {
        $this->authorizeSlot($slot);

        $validated = $request->validate([
            'status' => 'required|in:available,unavailable',
            'reason' => 'nullable|string|max:255',
        ]);

        $slot->update($validated);

        return back()->with('success', 'Slot updated.');
    }

    public function destroy(AvailabilitySlot $slot)
    {
        $this->authorizeSlot($slot);

        if ($slot->booking) {
            return back()->withErrors(['slot' => 'Cannot delete a slot with an active booking.']);
        }

        $slot->delete();

        return back()->with('success', 'Slot deleted.');
    }

    private function authorizeSlot(AvailabilitySlot $slot): void
    {
        $user = Auth::user();
        if ($slot->teacher_id !== $user->teacherProfile->id) {
            abort(403);
        }
    }
}
