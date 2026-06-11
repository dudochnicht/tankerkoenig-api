<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail;

use App\Tankerkoenig\Domain\Model\GasStationDetail\OpeningTime;
use App\Tankerkoenig\Domain\Model\GasStationDetail\StationDetail;
use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Mapper\MappingHelper;

final class StationDetailMapper
{
    use MappingHelper;

    public function __construct(
        private readonly OpeningTimeMapper $openingTimeMapper = new OpeningTimeMapper(),
    ) {
    }

    /**
     * @param array<string, mixed> $a json array
     * @throws MappingException
     */
    public function map(array $a): StationDetail
    {
        $openingTimes = array_map(
            fn (array $o): OpeningTime => $this->openingTimeMapper->map($o),
            self::getArray($a, 'openingTimes')
        );

        $overrides = array_map(
            fn (mixed $value): string => self::castToString($value),
            self::getArray($a, 'overrides')
        );

        return new StationDetail(
            id          : self::getString($a, 'id'),
            name        : self::getString($a, 'name'),
            brand       : self::getString($a, 'brand'),
            street      : self::getString($a, 'street'),
            houseNumber : self::getString($a, 'houseNumber'),
            place       : self::getString($a, 'place'),
            postCode    : self::getInt($a, 'postCode'),
            openingTimes: $openingTimes,
            overrides   : $overrides,
            wholeDay    : self::getBool($a, 'wholeDay'),
            lat         : self::getFloat($a, 'lat'),
            lng         : self::getFloat($a, 'lng'),
            isOpen      : self::getBool($a, 'isOpen'),
            diesel      : self::getNullableFloat($a, 'diesel'),
            e5          : self::getNullableFloat($a, 'e5'),
            e10         : self::getNullableFloat($a, 'e10'),
            state       : self::getNullableString($a, 'state'),
        );
    }
}
