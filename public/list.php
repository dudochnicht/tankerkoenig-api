<?php

declare(strict_types=1);

use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListRequest;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListResponse;
use Symfony\Component\Dotenv\Dotenv;
use App\Tankerkoenig\Domain\Enum\FuelType;
use App\Tankerkoenig\Domain\Enum\SortBy;
use App\Tankerkoenig\Domain\Exception\TankerkoenigException;
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

    $request = new GetGasStationListRequest(
        lat   : 52.521,
        lng   : 13.438,
        radius: 5.0,
        type  : FuelType::DIESEL,
        sort  : SortBy::PRICE,
    );

    /** @var GetGasStationListResponse $response */
    $response = $tankerkoenigClient->getList($request);

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
