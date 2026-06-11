<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationPrice;

use App\Tankerkoenig\Domain\Enum\Status;
use App\Tankerkoenig\Domain\Model\GasStationPrice\Price;
use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Mapper\MappingHelper;

final class PriceMapper
{
    use MappingHelper;

    /**
     * @param array<string, mixed> $a json array
     * @throws MappingException
     */
    public function map(string $id, array $a): Price
    {
        $rawStatus = self::getString($a, 'status');
        $status    = Status::tryFrom($rawStatus) ?? throw MappingException::invalidEnumValue('status', $rawStatus);

        return new Price(
            id    : $id,
            status: $status,
            e5    : self::getNullableFloat($a, 'e5'),
            e10   : self::getNullableFloat($a, 'e10'),
            diesel: self::getNullableFloat($a, 'diesel'),
        );
    }
}
