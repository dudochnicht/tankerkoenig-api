<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationList;

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Domain\Enum\FuelType;
use App\Tankerkoenig\Domain\Enum\SortBy;

final class GetGasStationListRequest
{
    /** @throws InvalidRequestException */
    public function __construct(
        private readonly float $lat,
        private readonly float $lng,
        private readonly float $radius,
        private readonly FuelType $type,
        private readonly SortBy $sort,
    ) {
        if ($this->lat < -90 || $this->lat > 90) {
            throw InvalidRequestException::invalidLat($this->lat);
        }

        if ($this->lng < -180 || $this->lng > 180) {
            throw InvalidRequestException::invalidLng($this->lng);
        }

        if ($this->radius < 1 || $this->radius > 25) {
            throw InvalidRequestException::invalidRadius($this->radius);
        }
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function getRadius(): float
    {
        return $this->radius;
    }

    public function getType(): FuelType
    {
        return $this->type;
    }

    public function getSort(): SortBy
    {
        return $this->sort;
    }
}
