<?php

declare(strict_types=1);

namespace App\Tests\Tankerkoenig\Infrastructure\Http\Mapper\GasStationList;

use App\Tankerkoenig\Domain\Model\GasStationList\StationList;
use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationList\StationListMapper;
use PHPUnit\Framework\TestCase;

final class StationListMapperTest extends TestCase
{
    private StationListMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new StationListMapper();
    }

    public function testMapValidData(): void
    {
        $data = [
            'stations' => [
                [
                    'id'          => '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8',
                    'name'        => 'ARAL Tankstelle',
                    'brand'       => 'ARAL',
                    'street'      => 'Musterstraße',
                    'houseNumber' => '1',
                    'place'       => 'Berlin',
                    'postCode'    => 10115,
                    'lat'         => 52.521918,
                    'lng'         => 13.413215,
                    'dist'        => 1.2,
                    'isOpen'      => true,
                    'diesel'      => 1.129,
                    'e5'          => null,
                    'e10'         => null,
                ]
            ]
        ];

        $stations = array_map(fn (array $a): StationList => $this->mapper->map($a), $data['stations']);

        $this->assertCount(1, $stations);
        $this->assertSame('4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8', $stations[0]->getId());
        $this->assertSame('ARAL Tankstelle', $stations[0]->getName());
        $this->assertSame(1.129, $stations[0]->getDiesel());
        $this->assertNull($stations[0]->getE5());
    }

    public function testMapMissingStationsKeyThrowsMappingException(): void
    {
        $this->expectException(MappingException::class);
        $this->mapper->map([]);
    }

    public function testMapEmptyStationsReturnsEmptyList(): void
    {
        $this->expectException(MappingException::class);
        $stations = array_map(fn (array $a): StationList => $this->mapper->map($a), ['stations' => []]);

        $this->assertCount(0, $stations);
    }
}
