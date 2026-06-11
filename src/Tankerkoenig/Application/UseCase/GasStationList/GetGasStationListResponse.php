<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationList;

use App\Tankerkoenig\Domain\Model\GasStationList\StationList;

final class GetGasStationListResponse
{
    /**
     * @param StationList[] $stations
     */
    public function __construct(
        private readonly array $stations
    ) {
    }

    /**
     * @return StationList[]
     */
    public function getStations(): array
    {
        return $this->stations;
    }
}
