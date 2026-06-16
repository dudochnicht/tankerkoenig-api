<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationPrice;

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Domain\Validation\Uuid;

final class GetGasStationPricesRequest
{
    /**
     * @param string[] $ids uuids
     * @throws InvalidRequestException
     */
    public function __construct(
        private readonly array $ids
    ) {

        if (empty($this->ids)) {
            throw throw InvalidRequestException::emptyIds();
        }

        // check if uuid is valid
        foreach ($this->ids as $id) {
            if (!Uuid::isValid($id)) {
                throw InvalidRequestException::invalidId($id);
            }
        }

        if (count($this->ids) > 10) {
            throw InvalidRequestException::tooManyIds(10, count($this->ids));
        }
    }

    /**
     * @return string[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
