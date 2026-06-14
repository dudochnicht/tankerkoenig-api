<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Enum;

enum SortBy: string
{
    case PRICE    = 'price';
    case DISTANCE = 'dist';
}
