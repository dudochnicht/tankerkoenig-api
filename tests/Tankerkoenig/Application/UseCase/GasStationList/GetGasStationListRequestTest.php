<?php

declare(strict_types=1);

namespace App\Tests\Tankerkoenig\Application\UseCase\GasStationList;

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListRequest;
use App\Tankerkoenig\Domain\Enum\FuelType;
use App\Tankerkoenig\Domain\Enum\Sort;
use PHPUnit\Framework\TestCase;

final class GetGasStationListRequestTest extends TestCase
{
    public function testValidRequest(): void
    {
        $request = new GetGasStationListRequest(
            lat   : 52.521918,
            lng   : 13.413215,
            radius: 5.0,
            type  : FuelType::DIESEL,
            sort  : Sort::PRICE,
        );

        $this->assertSame(52.521918, $request->getLat());
        $this->assertSame(13.413215, $request->getLng());
        $this->assertSame(5.0, $request->getRadius());
    }

    public function testInvalidLat(): void
    {
        $this->expectException(InvalidRequestException::class);

        new GetGasStationListRequest(
            lat   : 91.0, // invalid
            lng   : 13.413215,
            radius: 5.0,
            type  : FuelType::DIESEL,
            sort  : Sort::PRICE,
        );
    }

    public function testInvalidRadius(): void
    {
        $this->expectException(InvalidRequestException::class);

        new GetGasStationListRequest(
            lat   : 52.521918,
            lng   : 13.413215,
            radius: 30.0, // > 25km
            type  : FuelType::DIESEL,
            sort  : Sort::PRICE,
        );
    }
}
