<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail;

use App\Tankerkoenig\Domain\Model\GasStationDetail\OpeningTime;
use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Mapper\MappingHelper;

final class OpeningTimeMapper
{
    use MappingHelper;

    /**
     * @param array<string, mixed> $a json array
     * @throws MappingException
     */
    public function map(array $a): OpeningTime
    {
        return new OpeningTime(
            text : self::getString($a, 'text'),
            start: self::getString($a, 'start'),
            end  : self::getString($a, 'end'),
        );
    }
}
