<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationDetail;

use App\Tankerkoenig\Application\Exception\TankerkoenigException;
use App\Tankerkoenig\Domain\Repository\GasStationDetailRepositoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class GasStationDetailUseCase
{
    public function __construct(
        private GasStationDetailRepositoryInterface $repo,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    /**
     * @throws TankerkoenigException
     */
    public function getDetail(GetGasStationDetailRequest $req): GetGasStationDetailResponse
    {
        try {
            $stationDetail = $this->repo->findById($req->getId());

            return new GetGasStationDetailResponse($stationDetail);

        } catch (TankerkoenigException $e) {
            $this->logger->error('Failed to fetch gas station details', [
                'station_id' => $req->getId(),
                'exception'  => $e,
            ]);

            throw $e;
        }
    }
}
