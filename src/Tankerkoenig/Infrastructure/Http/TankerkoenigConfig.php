<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http;

final class TankerkoenigConfig
{
    public function __construct(
        private readonly string $apiKey,
        private readonly bool $debug = false,
        private readonly int $timeout = 10,
    ) {
        if (empty($this->apiKey)) {
            throw new \InvalidArgumentException('apiKey must not be empty');
        }
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
