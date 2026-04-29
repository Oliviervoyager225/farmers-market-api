<?php

declare(strict_types=1);

namespace App\Domain\Auth\Enums;

enum UserRole: string
{
    case Admin      = 'admin';
    case Supervisor = 'supervisor';
    case Operator   = 'operator';
}
