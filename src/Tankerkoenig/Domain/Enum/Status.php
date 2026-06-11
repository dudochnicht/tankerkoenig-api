<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Enum;

enum Status: string
{
    case OPEN        = 'open';
    case CLOSED      = 'closed';
    case NO_PRICES   = 'no prices';
    case NO_STATIONS = 'no stations';
}
