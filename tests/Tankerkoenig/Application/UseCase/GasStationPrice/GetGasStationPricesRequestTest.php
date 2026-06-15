<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig\Application\UseCase\GasStationPrice;

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesRequest;
use PHPUnit\Framework\TestCase;

final class GetGasStationPricesRequestTest extends TestCase
{
    public function testValidRequest(): void
    {
        $request = new GetGasStationPricesRequest(
            ids: [
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '446bdcf5-9f75-47fc-9cfa-2c3d6fda1c3b',
                '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8'
            ],
        );

        $this->assertSame([
            '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
            '446bdcf5-9f75-47fc-9cfa-2c3d6fda1c3b',
            '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8',
        ], $request->getIds());
    }

    public function testEmptyIds(): void
    {
        $this->expectException(InvalidRequestException::class);

        new GetGasStationPricesRequest(
            ids: [], // empty
        );
    }

    public function testTooManyIds(): void
    {
        $this->expectException(InvalidRequestException::class);

        new GetGasStationPricesRequest(
            ids: [
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
                '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
            ]
        );
    }
}
