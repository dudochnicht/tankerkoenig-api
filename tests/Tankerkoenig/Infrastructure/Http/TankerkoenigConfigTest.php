<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig\Infrastructure\Http;

use App\Tankerkoenig\Infrastructure\Http\TankerkoenigConfig;
use PHPUnit\Framework\TestCase;

final class TankerkoenigConfigTest extends TestCase
{
    public function testConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new TankerkoenigConfig(
            apiKey : '',
        );
    }
}
