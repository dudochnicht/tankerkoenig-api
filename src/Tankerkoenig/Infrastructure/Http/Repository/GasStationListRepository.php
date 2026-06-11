<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Repository;

use App\Tankerkoenig\Application\Exception\TankerkoenigException;
use App\Tankerkoenig\Domain\Enum\FuelType;
use App\Tankerkoenig\Domain\Enum\Sort;
use App\Tankerkoenig\Domain\Model\GasStationList\StationList;
use App\Tankerkoenig\Domain\Repository\GasStationListRepositoryInterface;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationList\StationListMapper;
use App\Tankerkoenig\Infrastructure\Http\TankerkoenigHttpClient;

final class GasStationListRepository implements GasStationListRepositoryInterface
{
    public function __construct(
        private readonly TankerkoenigHttpClient $client,
        private readonly StationListMapper $mapper,
    ) {
    }

    /**
     * @return StationList[]
     * @throws TankerkoenigException
     */
    public function findByParams(float $lat, float $lng, float $radius, FuelType $type, Sort $sort): array
    {
        /** @var array{stations: array<int, array<string, mixed>>} $data */
        $data = $this->client->get('json/list.php', [
            'lat' => $lat,
            'lng' => $lng,
            'rad' => $radius,
            'type' => $type->value,
            'sort' => $sort->value
        ]);

        try {
            /** @var StationList[] $stations */
            $stations = array_map(fn (array $a): StationList => $this->mapper->map($a), $data['stations']);

            return $stations;

        } catch (\Throwable $e) {
            throw TankerkoenigException::mappingFailed('GasStationList', $e);
        }
    }

}
