<?php

declare(strict_types=1);

namespace App\Tankerkoenig;

use App\Tankerkoenig\Application\UseCase\GasStationDetail\GasStationDetailUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailResponse;
use App\Tankerkoenig\Application\UseCase\GasStationList\GasStationListUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListRequest;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListResponse;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GasStationPricesUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesRequest;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesResponse;
use App\Tankerkoenig\Domain\Exception\TankerkoenigException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class TankerkoenigClient implements TankerkoenigClientInterface
{
    public function __construct(
        private readonly GasStationDetailUseCase $detailUseCase,
        private readonly GasStationListUseCase $listUseCase,
        private readonly GasStationPricesUseCase $pricesUseCase,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    /** @throws TankerkoenigException */
    public function getDetail(GetGasStationDetailRequest $req): GetGasStationDetailResponse
    {
        try {
            return $this->detailUseCase->getDetail($req);

        } catch (TankerkoenigException $e) {
            $this->logger->error('Failed to fetch gas station detail', [
                'station_id' => $req->getId(),
                'exception'  => $e,
            ]);

            throw $e;
        }
    }

    /** @throws TankerkoenigException */
    public function getList(GetGasStationListRequest $req): GetGasStationListResponse
    {
        try {
            return $this->listUseCase->getList($req);

        } catch (TankerkoenigException $e) {
            $this->logger->error('Failed to fetch gas station list', [
                'lat'       => $req->getLat(),
                'lng'       => $req->getLng(),
                'radius'    => $req->getRadius(),
                'type'      => $req->getType()->value,
                'sort'      => $req->getSort()->value,
                'exception' => $e,
            ]);

            throw $e;
        }
    }

    /** @throws TankerkoenigException */
    public function getPrices(GetGasStationPricesRequest $req): GetGasStationPricesResponse
    {
        try {
            return $this->pricesUseCase->getPrices($req);

        } catch (TankerkoenigException $e) {
            $this->logger->error('Failed to fetch gas station prices', [
                'station_ids' => $req->getIds(),
                'exception'   => $e,
            ]);

            throw $e;
        }
    }
}
