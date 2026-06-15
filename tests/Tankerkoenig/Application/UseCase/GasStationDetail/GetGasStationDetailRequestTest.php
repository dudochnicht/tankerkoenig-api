<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig\Application\UseCase\GasStationDetail;

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;
use PHPUnit\Framework\TestCase;

final class GetGasStationDetailRequestTest extends TestCase
{
    public function testValidRequest(): void
    {
        $request = new GetGasStationDetailRequest(
            id: '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
        );

        $this->assertSame('24a381e3-0d72-416d-bfd8-b2f65f6e5802', $request->getId());
    }

    public function testEmptyIdThrowsException(): void
    {
        $this->expectException(InvalidRequestException::class);

        new GetGasStationDetailRequest('');
    }
}
