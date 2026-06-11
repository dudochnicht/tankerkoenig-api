<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Model\GasStationList;

final class StationList
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $brand,
        private readonly string $street,
        private readonly string $houseNumber,
        private readonly string $place,
        private readonly int $postCode,
        private readonly float $lat,
        private readonly float $lng,
        private readonly float $dist,
        private readonly bool $isOpen,
        private readonly ?float $diesel,
        private readonly ?float $e5,
        private readonly ?float $e10,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function getPlace(): string
    {
        return $this->place;
    }

    public function getPostCode(): int
    {
        return $this->postCode;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function getDist(): float
    {
        return $this->dist;
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }

    public function getDiesel(): ?float
    {
        return $this->diesel;
    }

    public function getE5(): ?float
    {
        return $this->e5;
    }

    public function getE10(): ?float
    {
        return $this->e10;
    }
}
