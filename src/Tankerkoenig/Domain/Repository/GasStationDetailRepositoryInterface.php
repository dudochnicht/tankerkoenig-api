<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Repository;

use App\Tankerkoenig\Domain\Model\GasStationDetail\StationDetail;

interface GasStationDetailRepositoryInterface
{
    public function findById(string $id): StationDetail;
}
