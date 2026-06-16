<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig\Application\UseCase\GasStationDetail;

use App\Tankerkoenig\Application\UseCase\GasStationDetail\GasStationDetailUseCase;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailResponse;
use App\Tankerkoenig\Domain\Model\GasStationDetail\OpeningTime;
use App\Tankerkoenig\Domain\Model\GasStationDetail\StationDetail;
use App\Tankerkoenig\Domain\Repository\GasStationDetailRepositoryInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class GasStationDetailUseCaseTest extends TestCase
{
    private const VALID_ID = '24a381e3-0d72-416d-bfd8-b2f65f6e5802';

    private function buildStationDetail(): StationDetail
    {
        return new StationDetail(
            id:          self::VALID_ID,
            name:        'Esso Tankstelle',
            brand:       'ESSO',
            street:      'HAUPTSTR.',
            houseNumber: '7',
            place:       'MENGKOFEN',
            postCode:    84152,
            openingTimes: [
                new OpeningTime(
                    text : 'Mo-Fr',
                    start: new DateTimeImmutable('06:00:00'),
                    end  : new DateTimeImmutable('22:30:00'),
                ),
            ],
            overrides:   [],
            wholeDay:    false,
            lat:         48.72210601,
            lng:         12.44438439,
            isOpen:      false,
            diesel:      1.169,
            e5:          1.379,
            e10:         1.359,
            state:       null,
        );
    }

    public function testGetDetailDelegatesToRepository(): void
    {
        $stationDetail = $this->buildStationDetail();

        $repo = $this->createMock(GasStationDetailRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findById')
            ->with(self::VALID_ID)
            ->willReturn($stationDetail);

        $useCase  = new GasStationDetailUseCase($repo);
        $request  = new GetGasStationDetailRequest(id: self::VALID_ID);
        $response = $useCase->getDetail($request);

        $this->assertInstanceOf(GetGasStationDetailResponse::class, $response);
        $this->assertSame($stationDetail, $response->getStation());
    }

    public function testGetDetailReturnsCorrectStationId(): void
    {
        $stationDetail = $this->buildStationDetail();

        $repo = $this->createMock(GasStationDetailRepositoryInterface::class);
        $repo->method('findById')->willReturn($stationDetail);

        $useCase  = new GasStationDetailUseCase($repo);
        $request  = new GetGasStationDetailRequest(id: self::VALID_ID);
        $response = $useCase->getDetail($request);

        $this->assertSame(self::VALID_ID, $response->getStation()->getId());
    }

    public function testGetDetailPassesIdFromRequestToRepository(): void
    {
        $id   = '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8';
        $repo = $this->createMock(GasStationDetailRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findById')
            ->with($id);

        $useCase = new GasStationDetailUseCase($repo);
        $request = new GetGasStationDetailRequest(id: $id);

        try {
            $useCase->getDetail($request);
        } catch (\Throwable) {
        }
    }
}
