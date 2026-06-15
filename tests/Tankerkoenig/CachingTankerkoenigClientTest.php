<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig;

use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailResponse;
use App\Tankerkoenig\CachingTankerkoenigClient;
use App\Tankerkoenig\Domain\Model\GasStationDetail\OpeningTime;
use App\Tankerkoenig\Domain\Model\GasStationDetail\StationDetail;
use App\Tankerkoenig\TankerkoenigClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

final class CachingTankerkoenigClientTest extends TestCase
{
    public function testReturnsCachedResponseOnSecondCall(): void
    {
        $mockInner = $this->createMock(TankerkoenigClientInterface::class);
        $mockCache = $this->createMock(CacheInterface::class);

        $stationDetail = new StationDetail(
            id          : '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
            name        : 'Esso Tankstelle',
            brand       : 'ESSO',
            street      : 'HAUPTSTR.',
            houseNumber : '7',
            place       : 'MENGKOFEN',
            postCode    : 84152,
            openingTimes: [new OpeningTime(
                text : 'Mo-Fr',
                start: '06:00:00',
                end  : '22:30:00',
            )],
            overrides: ['13.04.2017, 15:00:00 - 13.11.2017, 15:00:00: geschlossen'],
            wholeDay : false,
            lat      : 48.72210601,
            lng      : 12.44438439,
            isOpen   : false,
            diesel   : 1.169,
            e5       : 1.379,
            e10      : 1.359,
            state    : null
        );

        $request  = new GetGasStationDetailRequest(id: '24a381e3-0d72-416d-bfd8-b2f65f6e5802');
        $response = new GetGasStationDetailResponse($stationDetail);

        $mockCache->method('get')->willReturn(null);
        $mockCache->expects($this->once())->method('set');
        $mockInner->expects($this->once())->method('getDetail')->willReturn($response);

        $client = new CachingTankerkoenigClient(
            inner: $mockInner,
            cache: $mockCache,
            ttl:   300,
        );

        $client->getDetail($request);
    }

    public function testHitSkipsInnerClient(): void
    {
        $mockInner = $this->createMock(TankerkoenigClientInterface::class);
        $mockCache = $this->createMock(CacheInterface::class);

        $stationDetail = new StationDetail(
            id          : '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
            name        : 'Esso Tankstelle',
            brand       : 'ESSO',
            street      : 'HAUPTSTR.',
            houseNumber : '7',
            place       : 'MENGKOFEN',
            postCode    : 84152,
            openingTimes: [new OpeningTime(
                text : 'Mo-Fr',
                start: '06:00:00',
                end  : '22:30:00',
            )],
            overrides: ['13.04.2017, 15:00:00 - 13.11.2017, 15:00:00: geschlossen'],
            wholeDay : false,
            lat      : 48.72210601,
            lng      : 12.44438439,
            isOpen   : false,
            diesel   : 1.169,
            e5       : 1.379,
            e10      : 1.359,
            state    : null
        );

        $request  = new GetGasStationDetailRequest(id: '24a381e3-0d72-416d-bfd8-b2f65f6e5802');
        $response = new GetGasStationDetailResponse($stationDetail);

        $mockCache->method('get')->willReturn($response);
        $mockInner->expects($this->never())->method('getDetail');

        $client = new CachingTankerkoenigClient(
            inner: $mockInner,
            cache: $mockCache,
        );

        $result = $client->getDetail($request);
        $this->assertSame($response, $result);
    }
}
