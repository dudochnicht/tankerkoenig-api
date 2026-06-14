<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationList;

use App\Tankerkoenig\Domain\Repository\GasStationListRepositoryInterface;

final class GasStationListUseCase
{
    public function __construct(
        private GasStationListRepositoryInterface $repo
    ) {
    }

    public function getList(GetGasStationListRequest $req): GetGasStationListResponse
    {
        $list = $this->repo->findByParams(
            lat   : $req->getLat(),
            lng   : $req->getLng(),
            radius: $req->getRadius(),
            type  : $req->getType(),
            sort  : $req->getSort()
        );

        return new GetGasStationListResponse($list);
    }
}
