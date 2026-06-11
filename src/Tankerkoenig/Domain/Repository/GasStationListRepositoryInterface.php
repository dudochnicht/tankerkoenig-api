<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Repository;

use App\Tankerkoenig\Domain\Enum\FuelType;
use App\Tankerkoenig\Domain\Enum\Sort;
use App\Tankerkoenig\Domain\Model\GasStationList\StationList;

interface GasStationListRepositoryInterface
{
    /**
     * @return StationList[]
     */
    public function findByParams(float $lat, float $lng, float $radius, FuelType $type, Sort $sort): array;
}
