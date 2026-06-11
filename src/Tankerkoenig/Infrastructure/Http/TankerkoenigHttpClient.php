<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http;

use App\Tankerkoenig\Application\Exception\TankerkoenigException;
use App\Tankerkoenig\TankerkoenigConfig;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class TankerkoenigHttpClient
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly TankerkoenigConfig $config,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    /**
     * @param array<string,mixed> $params
     * @return array<string, mixed>
     * @throws TankerkoenigException
     */
    public function get(string $endpoint, array $params = []): array
    {
        $params['apikey'] = $this->config->getApiKey();
        $query = http_build_query($params);
        $url   = sprintf('%s/%s?%s', $this->config->getBaseUrl(), $endpoint, $query);

        if ($this->config->isDebug()) {
            $this->logger->debug('GET {url} with params {params}', ['url' => $url, 'params' => $params]);
        }

        try {
            $request  = $this->requestFactory->createRequest('GET', $url);
            $response = $this->httpClient->sendRequest($request);
        } catch (\Throwable $e) {
            throw TankerkoenigException::apiFailed($endpoint, $e);
        }

        if ($response->getStatusCode() !== 200) {
            throw TankerkoenigException::apiFailed($endpoint);
        }

        /** @var array<mixed> $data */
        $data = json_decode($response->getBody()->getContents(), true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            throw TankerkoenigException::mappingFailed($endpoint);
        }

        if ($this->config->isDebug()) {
            $this->logger->debug('Returned {data}', ['data' => $data]);
        }

        if (($data['ok'] ?? true) === false) {
            $message = is_string($data['message'] ?? null) ? $data['message'] : 'unknown error';
            throw TankerkoenigException::notOk($endpoint, $message);
        }

        return $data;
    }
}
