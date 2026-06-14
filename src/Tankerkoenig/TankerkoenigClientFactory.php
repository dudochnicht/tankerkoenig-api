<?php

declare(strict_types=1);

namespace App\Tankerkoenig;

use App\Tankerkoenig\Application\UseCase\GasStationDetail\GasStationDetailUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationList\GasStationListUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GasStationPricesUseCase;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail\OpeningTimeMapper;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail\StationDetailMapper;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationList\StationListMapper;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationPrice\PriceMapper;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationDetailRepository;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationListRepository;
use App\Tankerkoenig\Infrastructure\Http\Repository\GasStationPricesRepository;
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
    ): TankerkoenigClient {

        $internalHttpClient = new TankerkoenigHttpClient(
            $httpClient,
            $requestFactory,
            $config,
            $logger
        );

        $detailRepository = new GasStationDetailRepository(
            $internalHttpClient,
            new StationDetailMapper(new OpeningTimeMapper())
        );

        $listRepository = new GasStationListRepository(
            $internalHttpClient,
            new StationListMapper()
        );

        $pricesRepository = new GasStationPricesRepository(
            $internalHttpClient,
            new PriceMapper()
        );

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
