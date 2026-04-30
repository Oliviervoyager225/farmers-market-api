<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Enums;

enum TransactionStatus: string
{
    case Paid    = 'paid';
    case Pending = 'pending';
}
