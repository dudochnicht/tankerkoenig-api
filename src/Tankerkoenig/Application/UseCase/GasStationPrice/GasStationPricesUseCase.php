<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationPrice;

use App\Tankerkoenig\Domain\Exception\TankerkoenigException;
use App\Tankerkoenig\Domain\Repository\GasStationPricesRepositoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class GasStationPricesUseCase
{
    /**
     * @param GasStationPricesRepositoryInterface $repo
     */
    public function __construct(
        private GasStationPricesRepositoryInterface $repo,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    /**
     * @throws TankerkoenigException
     */
    public function getPrices(GetGasStationPricesRequest $req): GetGasStationPricesResponse
    {
        try {

            $prices = $this->repo->findByIds($req->getIds());

            return new GetGasStationPricesResponse($prices);

        } catch (TankerkoenigException $e) {
            $this->logger->error('Failed to fetch gas station prices', [
                'station_ids' => $req->getIds(),
                'exception'   => $e,
            ]);

            throw $e;
        }
    }
}
