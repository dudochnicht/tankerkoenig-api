<?php

declare(strict_types=1);

namespace App\Tankerkoenig;

use App\Tankerkoenig\Application\UseCase\GasStationDetail\GasStationDetailUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationList\GasStationListUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GasStationPricesUseCase;
use App\Tankerkoenig\Infrastructure\Http\Mapper\MapperRegistry;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationDetailRepository;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationListRepository;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationPricesRepository;
use App\Tankerkoenig\Infrastructure\Http\TankerkoenigConfig;
use App\Tankerkoenig\Infrastructure\Http\TankerkoenigHttpClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class TankerkoenigClientFactory
{
    public static function create(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        TankerkoenigConfig $config,
        LoggerInterface $logger = new NullLogger()
    ): TankerkoenigClientInterface {

        $internalHttpClient = new TankerkoenigHttpClient(
            $httpClient,
            $requestFactory,
            $config,
            $logger
        );

        $registry = new MapperRegistry();

        $detailRepository = new GasStationDetailRepository($internalHttpClient, $registry->getStationDetailMapper());
        $listRepository   = new GasStationListRepository($internalHttpClient, $registry->getStationListMapper());
        $pricesRepository = new GasStationPricesRepository($internalHttpClient, $registry->getPriceMapper());

        $detailUseCase = new GasStationDetailUseCase($detailRepository);
        $listUseCase   = new GasStationListUseCase($listRepository);
        $pricesUseCase = new GasStationPricesUseCase($pricesRepository);

        return new TankerkoenigClient(
            $detailUseCase,
            $listUseCase,
            $pricesUseCase,
            $logger
        );
    }
}
