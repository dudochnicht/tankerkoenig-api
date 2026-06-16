<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig\Application\UseCase\GasStationList;

use App\Tankerkoenig\Application\UseCase\GasStationList\GasStationListUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListRequest;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListResponse;
use App\Tankerkoenig\Domain\Enum\FuelType;
use App\Tankerkoenig\Domain\Enum\SortBy;
use App\Tankerkoenig\Domain\Model\GasStationList\StationList;
use App\Tankerkoenig\Domain\Repository\GasStationListRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GasStationListUseCaseTest extends TestCase
{
    private function buildRequest(
        float    $lat    = 52.521,
        float    $lng    = 13.438,
        float    $radius = 5.0,
        FuelType $type   = FuelType::DIESEL,
        SortBy   $sort   = SortBy::PRICE,
    ): GetGasStationListRequest {
        return new GetGasStationListRequest(
            lat:    $lat,
            lng:    $lng,
            radius: $radius,
            type:   $type,
            sort:   $sort,
        );
    }

    private function buildStation(): StationList
    {
        return new StationList(
            id:          '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8',
            name:        'ARAL Tankstelle',
            brand:       'ARAL',
            street:      'Musterstraße',
            houseNumber: '1',
            place:       'Berlin',
            postCode:    10115,
            lat:         52.521918,
            lng:         13.413215,
            dist:        1.2,
            isOpen:      true,
            diesel:      1.129,
            e5:          null,
            e10:         null,
        );
    }

    public function testGetListDelegatesToRepository(): void
    {
        $stations = [$this->buildStation()];

        $repo = $this->createMock(GasStationListRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findByParams')
            ->willReturn($stations);

        $useCase  = new GasStationListUseCase($repo);
        $request  = $this->buildRequest();
        $response = $useCase->getList($request);

        $this->assertInstanceOf(GetGasStationListResponse::class, $response);
        $this->assertSame($stations, $response->getStations());
    }

    public function testGetListPassesAllParamsToRepository(): void
    {
        $repo = $this->createMock(GasStationListRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findByParams')
            ->with(52.521, 13.438, 5.0, FuelType::DIESEL, SortBy::PRICE)
            ->willReturn([]);

        $useCase = new GasStationListUseCase($repo);
        $useCase->getList($this->buildRequest());
    }

    public function testGetListReturnsEmptyArrayWhenNoStationsFound(): void
    {
        $repo = $this->createMock(GasStationListRepositoryInterface::class);
        $repo->method('findByParams')->willReturn([]);

        $useCase  = new GasStationListUseCase($repo);
        $response = $useCase->getList($this->buildRequest());

        $this->assertSame([], $response->getStations());
    }

    public function testGetListReturnsMultipleStations(): void
    {
        $stations = [$this->buildStation(), $this->buildStation()];

        $repo = $this->createMock(GasStationListRepositoryInterface::class);
        $repo->method('findByParams')->willReturn($stations);

        $useCase  = new GasStationListUseCase($repo);
        $response = $useCase->getList($this->buildRequest());

        $this->assertCount(2, $response->getStations());
    }

    public function testGetListWithDifferentFuelTypeAndSort(): void
    {
        $repo = $this->createMock(GasStationListRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findByParams')
            ->with(52.521, 13.438, 10.0, FuelType::E10, SortBy::DIST)
            ->willReturn([]);

        $useCase = new GasStationListUseCase($repo);
        $useCase->getList($this->buildRequest(radius: 10.0, type: FuelType::E10, sort: SortBy::DIST));
    }
}
