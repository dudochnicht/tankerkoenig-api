<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Domain\Model\GasStationDetail;

final class OpeningTime
{
    public function __construct(
        private readonly string $text,
        private readonly string $start,
        private readonly string $end
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getStart(): string
    {
        return $this->start;
    }

    public function getEnd(): string
    {
        return $this->end;
    }
}
