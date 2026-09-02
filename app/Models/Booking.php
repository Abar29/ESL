<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'student_id',
        'teacher_id',
        'slot_id',
        'status',
        'payment_method',
        'payment_reference',
        'screenshot_path',
        'held_until',
    ];

    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
            'payment_method' => PaymentMethod::class,
            'held_until' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(AvailabilitySlot::class, 'slot_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'booking_id');
    }

    public function isPendingPayment(): bool
    {
        return $this->status === BookingStatus::PendingPayment;
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::Confirmed;
    }

    public function isCompleted(): bool
    {
        return $this->status === BookingStatus::Completed;
    }
}
