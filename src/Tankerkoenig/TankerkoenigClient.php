<?php

declare(strict_types=1);

namespace App\Tankerkoenig;

use App\Tankerkoenig\Application\Exception\TankerkoenigException;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GasStationDetailUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailResponse;
use App\Tankerkoenig\Application\UseCase\GasStationList\GasStationListUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListRequest;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListResponse;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GasStationPricesUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesRequest;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesResponse;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail\OpeningTimeMapper;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail\StationDetailMapper;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationList\StationListMapper;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationPrice\PriceMapper;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationDetailRepository;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationListRepository;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationPricesRepository;
use App\Tankerkoenig\Infrastructure\Http\TankerkoenigHttpClient;
use App\Tankerkoenig\TankerkoenigConfig;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class TankerkoenigClient
{
    private GasStationDetailUseCase $detailUseCase;
    private GasStationListUseCase $listUseCase;
    private GasStationPricesUseCase $pricesUseCase;

    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        TankerkoenigConfig $config,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
        $client = new TankerkoenigHttpClient($httpClient, $requestFactory, $config, $logger);

        $this->detailUseCase = new GasStationDetailUseCase(
            new GasStationDetailRepository(
                $client,
                new StationDetailMapper(
                    new OpeningTimeMapper()
                )
            ),
            $logger
        );

        $this->listUseCase = new GasStationListUseCase(
            new GasStationListRepository(
                $client,
                new StationListMapper()
            ),
            $logger
        );

        $this->pricesUseCase = new GasStationPricesUseCase(
            new GasStationPricesRepository(
                $client,
                new PriceMapper()
            ),
            $logger
        );
    }

    /** @throws TankerkoenigException */
    public function getDetail(GetGasStationDetailRequest $req): GetGasStationDetailResponse
    {
        return $this->detailUseCase->getDetail($req);
    }

    /** @throws TankerkoenigException */
    public function getList(GetGasStationListRequest $req): GetGasStationListResponse
    {
        return $this->listUseCase->getList($req);
    }

    /** @throws TankerkoenigException */
    public function getPrices(GetGasStationPricesRequest $req): GetGasStationPricesResponse
    {
        return $this->pricesUseCase->getPrices($req);
    }
}
