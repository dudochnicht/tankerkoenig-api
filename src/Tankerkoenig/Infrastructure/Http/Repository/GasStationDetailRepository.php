<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Repository;

use App\Tankerkoenig\Application\Exception\TankerkoenigException;
use App\Tankerkoenig\Domain\Model\GasStationDetail\StationDetail;
use App\Tankerkoenig\Domain\Repository\GasStationDetailRepositoryInterface;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationDetail\StationDetailMapper;
use App\Tankerkoenig\Infrastructure\Http\TankerkoenigHttpClient;

final class GasStationDetailRepository implements GasStationDetailRepositoryInterface
{
    public function __construct(
        private readonly TankerkoenigHttpClient $client,
        private readonly StationDetailMapper $mapper,
    ) {
    }

    /**
     * @throws TankerkoenigException
     */
    public function findById(string $id): StationDetail
    {
        /** @var array{station: array<string, mixed>} $data */
        $data = $this->client->get('json/detail.php', ['id' => $id]);

        try {
            return $this->mapper->map($data['station']);

        } catch (\Throwable $e) {
            throw TankerkoenigException::mappingFailed('GasStationDetail', $e);
        }
    }

}
