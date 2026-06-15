<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig;

use App\Tankerkoenig\TankerkoenigConfig;
use PHPUnit\Framework\TestCase;

final class TankerkoenigConfigTest extends TestCase
{
    public function testRejectsHttpUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/HTTPS/i');

        new TankerkoenigConfig(
            apiKey : 'test-key',
            baseUrl: 'http://creativecommons.tankerkoenig.de',
        );
    }

    public function testAcceptsHttpsUrl(): void
    {
        $config = new TankerkoenigConfig(
            apiKey : 'test-key',
            baseUrl: 'https://creativecommons.tankerkoenig.de',
        );

        $this->assertSame('https://creativecommons.tankerkoenig.de', $config->getBaseUrl());
    }
}
