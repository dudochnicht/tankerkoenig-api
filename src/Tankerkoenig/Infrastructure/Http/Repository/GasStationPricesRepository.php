<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Repository;

use App\Tankerkoenig\Domain\Exception\TankerkoenigException;
use App\Tankerkoenig\Domain\Model\GasStationPrice\Price;
use App\Tankerkoenig\Domain\Repository\GasStationPricesRepositoryInterface;
use App\Tankerkoenig\Infrastructure\Http\Mapper\GasStationPrice\PriceMapper;
use App\Tankerkoenig\Infrastructure\Http\TankerkoenigHttpClient;

final class GasStationPricesRepository implements GasStationPricesRepositoryInterface
{
    public function __construct(
        private readonly TankerkoenigHttpClient $client,
        private readonly PriceMapper $mapper,
    ) {
    }

    /**
     * @param string[] $ids
     * @return Price[]
     * @throws TankerkoenigException
     */
    public function findByIds(array $ids): array
    {
        /** @var array{prices: array<string, array<string, mixed>>} $data */
        $data = $this->client->get('json/prices.php', ['ids' => implode(',', $ids)]);

        try {
            /** @var Price[] $prices */
            $prices = [];
            foreach ($data['prices'] as $id => $priceData) {
                $prices[] = $this->mapper->map($id, $priceData);
            }

            return $prices;

        } catch (\Throwable $e) {
            throw TankerkoenigException::mappingFailed('GasStationList', $e);
        }
    }

}
