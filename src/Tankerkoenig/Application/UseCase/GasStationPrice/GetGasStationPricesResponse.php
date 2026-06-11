<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationPrice;

use App\Tankerkoenig\Domain\Model\GasStationPrice\Price;

final class GetGasStationPricesResponse
{
    /**
     * @param Price[] $prices
     */
    public function __construct(
        private readonly array $prices
    ) {
    }

    /**
     * @return Price[]
     */
    public function getPrices(): array
    {
        return $this->prices;
    }
}
