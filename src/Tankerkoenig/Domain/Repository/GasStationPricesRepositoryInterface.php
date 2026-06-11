<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Repository;

use App\Tankerkoenig\Domain\Model\GasStationPrice\Price;

interface GasStationPricesRepositoryInterface
{
    /**
     * @param string[] $ids
     * @return Price[]
     */
    public function findByIds(array $ids): array;
}
