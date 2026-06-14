<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationList;

use App\Tankerkoenig\Domain\Exception\TankerkoenigException;
use App\Tankerkoenig\Domain\Repository\GasStationListRepositoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class GasStationListUseCase
{
    public function __construct(
        private GasStationListRepositoryInterface $repo,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    /**
     * @throws TankerkoenigException
     */
    public function getList(GetGasStationListRequest $req): GetGasStationListResponse
    {
        try {
            $list = $this->repo->findByParams(
                lat   : $req->getLat(),
                lng   : $req->getLng(),
                radius: $req->getRadius(),
                type  : $req->getType(),
                sort  : $req->getSort()
            );

            return new GetGasStationListResponse($list);

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
}
