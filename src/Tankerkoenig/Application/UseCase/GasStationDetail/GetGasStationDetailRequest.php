<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationDetail;

use App\Tankerkoenig\Application\Exception\InvalidRequestException;

final class GetGasStationDetailRequest
{
    /** @throws InvalidRequestException */
    public function __construct(
        private readonly string $id,
    ) {
        if (empty($this->id)) {
            throw InvalidRequestException::emptyId();
        }
    }

    public function getId(): string
    {
        return $this->id;
    }
}
