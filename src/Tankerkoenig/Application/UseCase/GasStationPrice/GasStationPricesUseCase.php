<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationPrice;

use App\Tankerkoenig\Domain\Repository\GasStationPricesRepositoryInterface;

final class GasStationPricesUseCase
{
    /**
     * @param GasStationPricesRepositoryInterface $repo
     */
    public function __construct(
        private GasStationPricesRepositoryInterface $repo
    ) {
    }

    public function getPrices(GetGasStationPricesRequest $req): GetGasStationPricesResponse
    {
        $prices = $this->repo->findByIds(
            ids: $req->getIds()
        );

        return new GetGasStationPricesResponse($prices);
    }
}
