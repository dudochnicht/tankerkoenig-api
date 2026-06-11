<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationList;

use App\Tankerkoenig\Domain\Model\GasStationList\StationList;
use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Mapper\MappingHelper;

final class StationListMapper
{
    use MappingHelper;

    /**
     * @param array<string, mixed> $a json array
     * @throws MappingException
     */
    public function map(array $a): StationList
    {
        return new StationList(
            id         : self::getString($a, 'id'),
            name       : self::getString($a, 'name'),
            brand      : self::getString($a, 'brand'),
            street     : self::getString($a, 'street'),
            houseNumber: self::getString($a, 'houseNumber'),
            place      : self::getString($a, 'place'),
            postCode   : self::getInt($a, 'postCode'),
            lat        : self::getFloat($a, 'lat'),
            lng        : self::getFloat($a, 'lng'),
            dist       : self::getFloat($a, 'dist'),
            isOpen     : self::getBool($a, 'isOpen'),
            diesel     : self::getNullableFloat($a, 'diesel'),
            e5         : self::getNullableFloat($a, 'e5'),
            e10        : self::getNullableFloat($a, 'e10'),
        );
    }
}
