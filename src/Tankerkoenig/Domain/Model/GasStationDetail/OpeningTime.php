<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Model\GasStationDetail;

final class OpeningTime
{
    public function __construct(
        private readonly string $text,
        private readonly \DateTimeImmutable $start,
        private readonly \DateTimeImmutable $end
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): \DateTimeImmutable
    {
        return $this->end;
    }
}
