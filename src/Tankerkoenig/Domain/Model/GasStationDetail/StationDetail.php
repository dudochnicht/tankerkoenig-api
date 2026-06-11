<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Model\GasStationDetail;

final class StationDetail
{
    /**
     * @param OpeningTime[] $openingTimes
     * @param string[] $overrides
     */
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $brand,
        private readonly string $street,
        private readonly string $houseNumber,
        private readonly string $place,
        private readonly int $postCode,
        private readonly array $openingTimes,
        private readonly array $overrides,
        private readonly bool $wholeDay,
        private readonly float $lat,
        private readonly float $lng,
        private readonly bool $isOpen,
        private readonly ?float $diesel,
        private readonly ?float $e5,
        private readonly ?float $e10,
        private readonly ?string $state
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

    /**
     * @return OpeningTime[]
     */
    public function getOpeningTimes(): array
    {
        return $this->openingTimes;
    }

    /**
     * @return string[]
     */
    public function getOverrides(): array
    {
        return $this->overrides;
    }

    public function isWholeDay(): bool
    {
        return $this->wholeDay;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
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

    public function getState(): ?string
    {
        return $this->state;
    }
}
