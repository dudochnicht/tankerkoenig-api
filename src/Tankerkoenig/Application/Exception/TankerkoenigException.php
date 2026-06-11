<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Application\Exception;

final class TankerkoenigException extends \RuntimeException
{
    public static function apiFailed(string $context, \Throwable $prev = null): self
    {
        return new self(sprintf('[%s] API request failed', $context), 0, $prev);
    }

    public static function mappingFailed(string $context, \Throwable $prev = null): self
    {
        return new self(sprintf('[%s] Response mapping failed', $context), 0, $prev);
    }

    public static function notOk(string $context, string $apiMessage): self
    {
        return new self(sprintf('[%s] API returned not ok: %s', $context, $apiMessage));
    }
}
