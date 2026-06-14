<?php

declare(strict_types=1);

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesRequest;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesResponse;
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
        debug : true,
    );

    $tankerkoenigClient = TankerkoenigClientFactory::create(
        httpClient:     $httpClient,
        requestFactory: $factory,
        config:         $tankerkoenigConfig,
        logger:         $logger,
    );

    $request = new GetGasStationPricesRequest(
        ids: [
            '60c0eefa-d2a8-4f5c-82cc-b5244ecae955',
            '446bdcf5-9f75-47fc-9cfa-2c3d6fda1c3b',
            '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8',
            '44444444-4444-4444-4444-444444444444',
        ],
    );

    /** @var GetGasStationPricesResponse $response */
    $response = $tankerkoenigClient->getPrices($request);

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
