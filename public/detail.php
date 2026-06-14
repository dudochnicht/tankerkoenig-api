<?php

declare(strict_types=1);

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailResponse;
use App\Tankerkoenig\Domain\Exception\TankerkoenigException;
use Symfony\Component\Dotenv\Dotenv;
use App\Tankerkoenig\TankerkoenigClientFactory;
use App\Tankerkoenig\TankerkoenigConfig;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\HttpClient\Psr18Client;

require_once dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv())->load(dirname(__DIR__) . '/.env');

try {

    $logger = new Logger('tankerkoenig');

    $logger->pushHandler(new StreamHandler(dirname(__DIR__) . '/var/logs/tankerkoenig.log', Level::Debug));
    $logger->pushHandler(new StreamHandler('php://stdout', Level::Debug));

    $factory    = new Psr17Factory();
    $httpClient = new Psr18Client(null, $factory, $factory);

    $tankerkoenigConfig = new TankerkoenigConfig(
        apiKey : $_ENV['TANKERKOENIG_API_KEY'] ?? throw new \RuntimeException('TANKERKOENIG_API_KEY is not set'),
        baseUrl: $_ENV['TANKERKOENIG_BASE_URL'] ?? throw new \RuntimeException('TANKERKOENIG_BASE_URL is not set'),
        debug : (bool) ($_ENV['TANKERKOENIG_DEBUG'] ?? false),
    );

    $tankerkoenigClient = TankerkoenigClientFactory::create(
        httpClient:     $httpClient,
        requestFactory: $factory,
        config:         $tankerkoenigConfig,
        logger:         $logger,
    );

    $request = new GetGasStationDetailRequest(
        id   : '24a381e3-0d72-416d-bfd8-b2f65f6e5802',
    );

    /** @var GetGasStationDetailResponse $response */
    $response = $tankerkoenigClient->getDetail($request);

    echo "<pre>";
    print_r($response);
    echo "</pre>";

} catch (InvalidRequestException $e) {
    http_response_code(400);
    echo json_encode([
        'error' => 'invalid_request',
        'message' => $e->getMessage(),
    ]);
} catch (TankerkoenigException $e) {
    http_response_code(502);
    echo json_encode([
        'error' => 'api_error',
        'message' => $e->getMessage(),
    ]);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'internal_error',
        'message' => $e->getMessage(),
    ]);
}
