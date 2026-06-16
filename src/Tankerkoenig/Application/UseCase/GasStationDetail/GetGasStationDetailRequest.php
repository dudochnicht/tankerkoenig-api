<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\UseCase\GasStationDetail;

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Domain\Validation\Uuid;

final class GetGasStationDetailRequest
{
    /** @throws InvalidRequestException */
    public function __construct(
        private readonly string $id,
    ) {
        if (!Uuid::isValid($id)) {
            throw InvalidRequestException::invalidId($id);
        }
    }

    public function getId(): string
    {
        return $this->id;
    }
}
