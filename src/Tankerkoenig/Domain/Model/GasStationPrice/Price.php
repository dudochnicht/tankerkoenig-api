<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Model\GasStationPrice;

use App\Tankerkoenig\Domain\Enum\Status;

final class Price
{
    public function __construct(
        private readonly string $id,
        private readonly Status $status,
        private readonly ?float $e5,
        private readonly ?float $e10,
        private readonly ?float $diesel,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getE5(): ?float
    {
        return $this->e5;
    }

    public function getE10(): ?float
    {
        return $this->e10;
    }

    public function getDiesel(): ?float
    {
        return $this->diesel;
    }
}
