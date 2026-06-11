<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationDetail;

use App\Tankerkoenig\Domain\Model\GasStationDetail\StationDetail;

final class GetGasStationDetailResponse
{
    public function __construct(
        private readonly StationDetail $station
    ) {
    }

    public function getStation(): StationDetail
    {
        return $this->station;
    }
}
