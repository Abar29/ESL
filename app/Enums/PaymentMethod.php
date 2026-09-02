<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case GCash = 'gcash';
    case GoTyme = 'gotyme';
    case Maya = 'maya';
}
