<?php

namespace App\Models;

use App\Enums\SlotStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AvailabilitySlot extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'teacher_id',
        'slot_date',
        'start_time',
        'end_time',
        'status',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'slot_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'status' => SlotStatus::class,
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'slot_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === SlotStatus::Available;
    }

    public function isHeld(): bool
    {
        return $this->status === SlotStatus::Held;
    }
}
