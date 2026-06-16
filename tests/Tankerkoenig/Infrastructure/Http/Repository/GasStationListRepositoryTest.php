<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig\Infrastructure\Http\Repository;

use PHPUnit\Framework\TestCase;

final class GasStationListRepositoryTest extends TestCase
{
    public function testfindById(): void
    {
        $id = '24a381e3-0d72-416d-bfd8-b2f65f6e5802';
        $this->assertSame('24a381e3-0d72-416d-bfd8-b2f65f6e5802', $id);
    }
}
