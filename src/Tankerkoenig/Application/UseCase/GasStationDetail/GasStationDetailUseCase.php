<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationDetail;

use App\Tankerkoenig\Domain\Repository\GasStationDetailRepositoryInterface;

final class GasStationDetailUseCase
{
    public function __construct(
        private GasStationDetailRepositoryInterface $repo,
    ) {
    }

    public function getDetail(GetGasStationDetailRequest $req): GetGasStationDetailResponse
    {
        $stationDetail = $this->repo->findById(
            id: $req->getId()
        );

        return new GetGasStationDetailResponse($stationDetail);
    }
}
