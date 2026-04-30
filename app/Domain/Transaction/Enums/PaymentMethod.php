<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Enums;

enum PaymentMethod: string
{
    case Cash   = 'cash';
    case Credit = 'credit';
}
