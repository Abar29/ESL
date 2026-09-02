<?php

namespace App\Enums;

enum SlotStatus: string
{
    case Available = 'available';
    case Held = 'held';
    case Booked = 'booked';
    case Unavailable = 'unavailable';
}
