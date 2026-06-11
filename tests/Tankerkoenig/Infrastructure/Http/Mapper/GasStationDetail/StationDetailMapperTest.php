<?php

declare(strict_types=1);

namespace App\Tests\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail;

use App\Tankerkoenig\Domain\Model\GasStationDetail\StationDetail;
use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail\StationDetailMapper;
use PHPUnit\Framework\TestCase;

final class StationDetailMapperTest extends TestCase
{
    private StationDetailMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new StationDetailMapper();
    }

    private function validStationData(): array
    {
        return [
            'id'           => '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
            'name'         => 'Esso Tankstelle',
            'brand'        => 'ESSO',
            'street'       => 'HAUPTSTR. 7',
            'houseNumber'  => ' ',
            'postCode'     => 84152,
            'place'        => 'MENGKOFEN',
            'openingTimes' => [
                ['text' => 'Mo-Fr',    'start' => '06:00:00', 'end' => '22:30:00'],
                ['text' => 'Samstag',  'start' => '07:00:00', 'end' => '22:00:00'],
                ['text' => 'Sonntag',  'start' => '08:00:00', 'end' => '22:00:00'],
            ],
            'overrides' => [
                '13.04.2017, 15:00:00 - 13.11.2017, 15:00:00: geschlossen'
            ],
            'wholeDay' => false,
            'isOpen'   => false,
            'e5'       => 1.379,
            'e10'      => 1.359,
            'diesel'   => 1.169,
            'lat'      => 48.72210601,
            'lng'      => 12.44438439,
            'state'    => null,
        ];
    }

    public function testMapValidData(): void
    {
        $station = $this->mapper->map($this->validStationData());

        $this->assertInstanceOf(StationDetail::class, $station);
        $this->assertSame('24a381e3-0d72-416d-bfd8-b2f65f6e5802', $station->getId());
        $this->assertSame('Esso Tankstelle', $station->getName());
        $this->assertSame('ESSO', $station->getBrand());
        $this->assertSame(1.169, $station->getDiesel());
        $this->assertSame(1.379, $station->getE5());
        $this->assertSame(1.359, $station->getE10());
        $this->assertFalse($station->isOpen());
        $this->assertFalse($station->isWholeDay());
        $this->assertNull($station->getState());
    }

    public function testMapOpeningTimes(): void
    {
        $station      = $this->mapper->map($this->validStationData());
        $openingTimes = $station->getOpeningTimes();

        $this->assertCount(3, $openingTimes);
        $this->assertSame('Mo-Fr', $openingTimes[0]->getText());
        $this->assertSame('06:00:00', $openingTimes[0]->getStart());
        $this->assertSame('22:30:00', $openingTimes[0]->getEnd());
    }

    public function testMapOverrides(): void
    {
        $station = $this->mapper->map($this->validStationData());

        $this->assertCount(1, $station->getOverrides());
        $this->assertSame(
            '13.04.2017, 15:00:00 - 13.11.2017, 15:00:00: geschlossen',
            $station->getOverrides()[0]
        );
    }

    public function testMapNullablePrices(): void
    {
        $data           = $this->validStationData();
        $data['e5']     = null;
        $data['e10']    = null;
        $data['diesel'] = null;

        $station = $this->mapper->map($data);

        $this->assertNull($station->getE5());
        $this->assertNull($station->getE10());
        $this->assertNull($station->getDiesel());
    }

    public function testMapMissingRequiredFieldThrowsMappingException(): void
    {
        $this->expectException(MappingException::class);
        $this->mapper->map([]);
    }

    public function testMapMissingIdThrowsMappingException(): void
    {
        $data = $this->validStationData();
        unset($data['id']);

        $this->expectException(MappingException::class);
        $this->mapper->map($data);
    }
}
