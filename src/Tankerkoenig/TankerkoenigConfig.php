<?php

declare(strict_types=1);

namespace App\Tankerkoenig;

final class TankerkoenigConfig
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
        private readonly bool $debug = false,
        private readonly int $timeout = 10,
    ) {
        if (empty($this->apiKey)) {
            throw new \InvalidArgumentException('apiKey must not be empty');
        }

        if (!str_starts_with($this->baseUrl, 'https://')) {
            throw new \InvalidArgumentException(
                sprintf("baseUrl must use HTTPS, '%s' given", $this->baseUrl)
            );
        }

        if ($this->timeout < 1 || $this->timeout > 60) {
            throw new \InvalidArgumentException(
                sprintf('timeout must be between 1 and 60 seconds, %d given', $this->timeout)
            );
        }
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

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
