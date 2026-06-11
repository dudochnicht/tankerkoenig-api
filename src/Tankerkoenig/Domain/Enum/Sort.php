<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Enum;

enum Sort: string
{
    case PRICE    = 'price';
    case DISTANCE = 'dist';
}
