<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Enum;

enum FuelType: string
{
    case E5     = 'e5';
    case E10    = 'e10';
    case DIESEL = 'diesel';
    case ALL    = 'all';
}
