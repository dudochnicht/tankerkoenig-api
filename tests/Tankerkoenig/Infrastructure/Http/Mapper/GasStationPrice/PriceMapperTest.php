<?php

declare(strict_types=1);

namespace App\Tests\Tankerkoenig\Infrastructure\Http\Mapper\GasStationPrice;

use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationPrice\PriceMapper;
use PHPUnit\Framework\TestCase;

final class PriceMapperTest extends TestCase
{
    private PriceMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new PriceMapper();
    }

    private function validPricesData(): array
    {
        return [
            'prices' => [
                '60c0eefa-d2a8-4f5c-82cc-b5244ecae955' => [
                    'status'  => 'open',
                    'e5'      => false,
                    'e10'     => false,
                    'diesel'  => 1.189,
                ],
                '446bdcf5-9f75-47fc-9cfa-2c3d6fda1c3b' => [
                    'status'  => 'closed',
                ],
                '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8' => [
                    'status'  => 'open',
                    'e5'      => 1.409,
                    'e10'     => 1.389,
                    'diesel'  => 1.129,
                ],
                '44444444-4444-4444-4444-444444444444' => [
                    'status'  => 'no prices',
                ],
            ]
        ];
    }

    public function testMapValidData(): void
    {
        $data = $this->validPricesData();

        $prices = [];
        foreach ($data['prices'] as $id => $priceData) {
            $prices[] = $this->mapper->map($id, $priceData);
        }

        $this->assertCount(4, $prices);
    }

    public function testMapOpenStationWithAllFuels(): void
    {
        $price = $this->mapper->map(
            '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8',
            ['status' => 'open', 'e5' => 1.409, 'e10' => 1.389, 'diesel' => 1.129]
        );

        $this->assertSame('4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8', $price->getId());
        $this->assertSame(1.409, $price->getE5());
        $this->assertSame(1.389, $price->getE10());
        $this->assertSame(1.129, $price->getDiesel());
    }

    public function testMapOpenStationWithOnlyDiesel(): void
    {
        $price = $this->mapper->map(
            '60c0eefa-d2a8-4f5c-82cc-b5244ecae955',
            ['status' => 'open', 'e5' => false, 'e10' => false, 'diesel' => 1.189]
        );

        $this->assertNull($price->getE5());
        $this->assertNull($price->getE10());
        $this->assertSame(1.189, $price->getDiesel());
    }

    public function testMapClosedStation(): void
    {
        $price = $this->mapper->map(
            '446bdcf5-9f75-47fc-9cfa-2c3d6fda1c3b',
            ['status' => 'closed']
        );

        $this->assertSame('closed', $price->getStatus()->value);
        $this->assertNull($price->getE5());
        $this->assertNull($price->getE10());
        $this->assertNull($price->getDiesel());
    }

    public function testMapNoPricesStation(): void
    {
        $price = $this->mapper->map(
            '44444444-4444-4444-4444-444444444444',
            ['status' => 'no prices']
        );

        $this->assertSame('no prices', $price->getStatus()->value);
        $this->assertNull($price->getE5());
        $this->assertNull($price->getE10());
        $this->assertNull($price->getDiesel());
    }

    public function testMapInvalidStatusThrowsMappingException(): void
    {
        $this->expectException(MappingException::class);
        $this->mapper->map(
            '44444444-4444-4444-4444-444444444444',
            ['status' => 'invalid_status']
        );
    }

    public function testMapMissingStatusThrowsMappingException(): void
    {
        $this->expectException(MappingException::class);
        $this->mapper->map('44444444-4444-4444-4444-444444444444', []);
    }
}
