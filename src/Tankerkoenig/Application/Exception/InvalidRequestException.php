<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\Exception;

final class InvalidRequestException extends \InvalidArgumentException
{
    public static function emptyId(): self
    {
        return new self('id must not be empty');
    }

    public static function emptyIds(): self
    {
        return new self('ids must not be empty');
    }

    public static function tooManyIds(int $max, int $given): self
    {
        return new self(sprintf('ids must not be greater than %d, %d given', $max, $given));
    }

    public static function invalidLat(float $lat): self
    {
        return new self(sprintf("lat '%s' must be between -90 and 90", $lat));
    }

    public static function invalidLng(float $lng): self
    {
        return new self(sprintf("lng '%s' must be between -180 and 180", $lng));
    }

    public static function invalidRadius(float $radius): self
    {
        return new self(sprintf("rad '%s' must be between 1 and 25 km", $radius));
    }
}
