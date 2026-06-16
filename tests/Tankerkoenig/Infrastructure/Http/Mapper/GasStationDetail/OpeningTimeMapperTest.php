<?php

declare(strict_types=1);

namespace Tests\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail;

use App\Tankerkoenig\Domain\Model\GasStationDetail\OpeningTime;
use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail\OpeningTimeMapper;
use PHPUnit\Framework\TestCase;

final class OpeningTimeMapperTest extends TestCase
{
    private OpeningTimeMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new OpeningTimeMapper();
    }

    public function testMapValidData(): void
    {
        $result = $this->mapper->map([
            'text'  => 'Mo-Fr',
            'start' => '06:00:00',
            'end'   => '22:30:00',
        ]);

        $this->assertInstanceOf(OpeningTime::class, $result);
        $this->assertSame('Mo-Fr', $result->getText());
        $this->assertSame('06:00:00', $result->getStart()->format('H:i:s'));
        $this->assertSame('22:30:00', $result->getEnd()->format('H:i:s'));
    }

    public function testMapMissingTextField(): void
    {
        $this->expectException(MappingException::class);

        $this->mapper->map(['start' => '06:00:00', 'end' => '22:30:00']);
    }

    public function testMapMissingStartField(): void
    {
        $this->expectException(MappingException::class);

        $this->mapper->map(['text' => 'Mo-Fr', 'end' => '22:30:00']);
    }

    public function testMapMissingEndField(): void
    {
        $this->expectException(MappingException::class);

        $this->mapper->map(['text' => 'Mo-Fr', 'start' => '06:00:00']);
    }

    public function testMapWrongTypeThrowsMappingException(): void
    {
        $this->expectException(MappingException::class);

        $this->mapper->map(['text' => 123, 'start' => '06:00:00', 'end' => '22:30:00']);
    }

    public function testMapEmptyArray(): void
    {
        $this->expectException(MappingException::class);

        $this->mapper->map([]);
    }

    public function testMapWithEmptyStrings(): void
    {
        $result = $this->mapper->map([
            'text'  => '',
            'start' => '',
            'end'   => '',
        ]);

        $this->assertSame('', $result->getText());
        $this->assertSame(date('H:i:s'), $result->getStart()->format('H:i:s'));
        $this->assertSame(date('H:i:s'), $result->getEnd()->format('H:i:s'));
    }
}
