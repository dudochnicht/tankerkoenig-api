<?php

declare(strict_types=1);

namespace App\Tankerkoenig;

final class TankerkoenigConfig
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
        private readonly bool $debug = false,
    ) {
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }
}
