<?php

declare(strict_types=1);

namespace App\Tankerkoenig;

use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailResponse;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListRequest;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListResponse;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesRequest;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesResponse;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;

final class CachingTankerkoenigClient implements TankerkoenigClientInterface
{
    private const DEFAULT_TTL = 300;

    public function __construct(
        private readonly TankerkoenigClientInterface $inner,
        private readonly CacheInterface $cache,
        private readonly int $ttl = self::DEFAULT_TTL,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    public function getDetail(GetGasStationDetailRequest $req): GetGasStationDetailResponse
    {
        $key = 'tankerkoenig_detail_' . md5($req->getId());

        $cached = $this->cache->get($key);
        if ($cached instanceof GetGasStationDetailResponse) {

            $this->logger->info('Fetching gas station detail from cache', ['key' => $key]);

            return $cached;
        }

        $response = $this->inner->getDetail($req);
        $this->cache->set($key, $response, $this->ttl);

        return $response;
    }

    public function getList(GetGasStationListRequest $req): GetGasStationListResponse
    {
        $key = sprintf(
            'tankerkoenig_list_%s',
            md5(implode('_', [
                $req->getLat(),
                $req->getLng(),
                $req->getRadius(),
                $req->getType()->value,
                $req->getSort()->value,
            ]))
        );

        $cached = $this->cache->get($key);
        if ($cached instanceof GetGasStationListResponse) {

            $this->logger->info('Fetching gas station list from cache', ['key' => $key]);

            return $cached;
        }

        $response = $this->inner->getList($req);
        $this->cache->set($key, $response, $this->ttl);

        return $response;
    }

    public function getPrices(GetGasStationPricesRequest $req): GetGasStationPricesResponse
    {
        $ids = $req->getIds();
        sort($ids);
        $key = 'tankerkoenig_prices_' . md5(implode(',', $ids));

        $cached = $this->cache->get($key);
        if ($cached instanceof GetGasStationPricesResponse) {

            $this->logger->info('Fetching gas station prices from cache', ['key' => $key]);

            return $cached;
        }

        $response = $this->inner->getPrices($req);
        $this->cache->set($key, $response, $this->ttl);

        return $response;
    }
}
