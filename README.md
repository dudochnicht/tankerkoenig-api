# Tankerkoenig PHP Client

A PHP library for the [Tankerkoenig API](https://creativecommons.tankerkoenig.de) built with Clean
Architecture principles.

The library is HTTP client agnostic — bring your own PSR-18 compatible implementation.

## Requirements

- PHP 8.2+
- PSR-18 HTTP Client implementation
- PSR-17 HTTP Factory implementation

## Installation

```bash
git clone https://github.com/dudochnicht/tankerkoenig-api.git
cd tankerkoenig
composer install
```

Then install a PSR-18 compatible HTTP client of your choice:

### Symfony HttpClient

```bash
composer require symfony/http-client nyholm/psr7
```

### Guzzle

```bash
composer require guzzlehttp/guzzle
```

## Configuration

Create a `.env` file in your project root:

```env
TANKERKOENIG_API_KEY=your-api-key
TANKERKOENIG_BASE_URL=base-url-from-tankerkoenig
TANKERKOENIG_DEBUG_MODE=false
```

Get your API key at [creativecommons.tankerkoenig.de](https://creativecommons.tankerkoenig.de).

## Usage

### Setup with Symfony HttpClient

```php
use App\Tankerkoenig\TankerkoenigClient;
use App\Tankerkoenig\TankerkoenigConfig;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\HttpClient\Psr18Client;

$factory    = new Psr17Factory();
$httpClient = new Psr18Client(null, $factory, $factory);

$client = new TankerkoenigClient(
    httpClient     : $httpClient,
    requestFactory : $factory,
    config         : new TankerkoenigConfig(
        apiKey : $_ENV['TANKERKOENIG_API_KEY'],
        baseUrl: $_ENV['TANKERKOENIG_BASE_URL'],
        debug  : (bool) $_ENV['TANKERKOENIG_DEBUG_MODE'],
    ),
);
```

### Setup with Guzzle

```php
use App\Tankerkoenig\TankerkoenigClient;
use App\Tankerkoenig\TankerkoenigConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

$factory    = new HttpFactory();
$httpClient = new Client();

$client = new TankerkoenigClient(
    httpClient     : $httpClient,
    requestFactory : $factory,
    config         : new TankerkoenigConfig(
        apiKey : $_ENV['TANKERKOENIG_API_KEY'],
        baseUrl: $_ENV['TANKERKOENIG_BASE_URL'],
        debug  : (bool) $_ENV['TANKERKOENIG_DEBUG_MODE'],
    ),
);
```

### Optional: Logger (PSR-3)

The client accepts any PSR-3 compatible logger — for example Monolog:

```bash
composer require monolog/monolog
```

```php
use App\Tankerkoenig\TankerkoenigClient;
use App\Tankerkoenig\TankerkoenigConfig;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

$logger = new Logger('tankerkoenig');
$logger->pushHandler(new StreamHandler('/var/logs/tankerkoenig.log', Level::Debug));
$logger->pushHandler(new StreamHandler('php://stdout', Level::Debug));

$client = new TankerkoenigClient(
    httpClient     : $httpClient,
    requestFactory : $factory,
    config         : new TankerkoenigConfig(
        apiKey : $_ENV['TANKERKOENIG_API_KEY'],
        baseUrl: $_ENV['TANKERKOENIG_BASE_URL'],
        debug  : (bool) $_ENV['TANKERKOENIG_DEBUG_MODE'],
    ),
    logger         : $logger,
);
```

## Gas Station List

```php
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListRequest;
use App\Tankerkoenig\Domain\Enum\FuelType;
use App\Tankerkoenig\Domain\Enum\SortBy;

$request = new GetGasStationListRequest(
    lat   : 52.521918,
    lng   : 13.413215,
    radius: 5.0,
    type  : FuelType::DIESEL,
    sort  : Sort::PRICE,
);

$response = $client->getList($request);

print_r($response);
```

## Gas Station Detail

```php
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;

$request  = new GetGasStationDetailRequest(
    id: '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8'
);

$response = $client->getDetail($request);

print_r($response);
```

## Gas Station Prices

```php
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesRequest;

$request = new GetGasStationPricesRequest(ids: [
    '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8',
    '446bdcf5-9f75-47fc-9cfa-2c3d6fda1c3b',
]);

$response = $client->getPrices($request);

print_r($response);
```

## Error Handling

```php
use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Application\Exception\TankerkoenigException;

try {
    $response = $client->getGasStationList($request);

} catch (InvalidRequestException $e) {
    // Invalid request parameters (e.g. radius > 25 km, empty IDs)
    http_response_code(400);
    echo json_encode(['error' => 'invalid_request', 'message' => $e->getMessage()]);

} catch (TankerkoenigException $e) {
    // API error or unexpected response structure — check your logger for details
    http_response_code(502);
    echo json_encode(['error' => 'api_error', 'message' => $e->getMessage()]);

} catch (\Throwable $e) {
    // Unexpected error
    http_response_code(500);
    echo json_encode(['error' => 'internal_error', 'message' => $e->getMessage()]);
}
```

> **Note:** `TankerkoenigException` covers both API failures and mapping errors. The original cause
> is always available via `$e->getPrevious()` and is written to your logger automatically.

## Request Validation

Requests validate themselves on construction — invalid objects cannot exist:

| Parameter | Validation                    |
| --------- | ----------------------------- |
| `lat`     | Must be between -90 and 90    |
| `lng`     | Must be between -180 and 180  |
| `radius`  | Must be between 1 and 25 km   |
| `ids`     | Must not be empty, max 10 IDs |

## Architecture

This library follows **Clean Architecture** principles with a strict dependency rule — inner layers
never depend on outer layers.

```
src/
└── Tankerkoenig/
    ├── TankerkoenigClient.php       # Public entry point
    ├── TankerkoenigConfig.php       # Configuration
    ├── Domain/                      # Innermost layer — no dependencies
    │   ├── Enum/
    │   │   ├── FuelType.php
    │   │   ├── Sort.php
    │   │   └── Status.php
    │   ├── Model/
    │   │   ├── GasStationDetail/
    │   │   │   ├── StationDetail.php
    │   │   │   └── OpeningTime.php
    │   │   ├── GasStationList/
    │   │   │   ├── Station.php
    │   │   │   └── StationList.php
    │   │   └── GasStationPrice/
    │   │       └── Price.php
    │   └── Repository/
    │       ├── GasStationDetailRepositoryInterface.php
    │       ├── GasStationListRepositoryInterface.php
    │       └── GasStationPricesRepositoryInterface.php
    ├── Application/                 # Use cases — depends only on Domain
    │   ├── Exception/
    │   │   ├── InvalidRequestException.php
    │   │   └── TankerkoenigException.php
    │   └── UseCase/
    │       ├── GasStationDetail/
    │       │   ├── GasStationDetailUseCase.php
    │       │   ├── GetGasStationDetailRequest.php
    │       │   └── GetGasStationDetailResponse.php
    │       ├── GasStationList/
    │       └── GasStationPrice/
    └── Infrastructure/              # Adapters — implements Domain ports
        └── Http/
            ├── TankerkoenigHttpClient.php
            ├── Exception/
            │   ├── ApiException.php
            │   └── MappingException.php
            ├── Mapper/
            │   ├── MappingHelper.php
            │   ├── GasStationDetail/
            │   │   ├── StationDetailMapper.php
            │   │   └── OpeningTimeMapper.php
            │   ├── GasStationList/
            │   │   ├── StationListMapper.php
            │   └── GasStationPrice/
            │       └── PriceMapper.php
            ├── Trait/
            │   └── CastHelper.php
            └── Repository/
                ├── GasStationDetailRepository.php
                ├── GasStationListRepository.php
                └── GasStationPricesRepository.php
```

### Dependency Rule

```
TankerkoenigClient → UseCase → [RepositoryInterface] ← Repository
                                                              ↓
                                                    TankerkoenigHttpClient
                                                              ↓
                                                          Mapper
                                                              ↓
                                                       Domain Model
```

### Design Principles

- **Clean Architecture** — strict unidirectional dependency rule
- **Dependency Inversion** — use cases depend on repository interfaces, never on concrete
  implementations
- **PSR-18 / PSR-17** — HTTP client agnostic, bring your own implementation
- **PSR-3** — logger agnostic, bring your own implementation
- **Fail Fast** — request validation on construction, invalid objects cannot exist
- **Immutability** — all models and requests are `readonly` and `final`
- **Single Exception Surface** — `TankerkoenigException` wraps all infrastructure errors; original
  cause preserved via `getPrevious()`

## Docker

A minimal Alpine PHP Docker setup is included for development:

```bash
cp .env.example .env
# Add your API key to .env

docker compose up -d
```

The API is available at `http://localhost:8081`.

## Development

```bash
# Static analysis
composer phpstan

# Tests
composer phpunit
```

## License

MIT License. See [LICENSE](LICENSE) for details.

## Data License

Tankerkoenig data is licensed under [CC BY 4.0](https://creativecommons.tankerkoenig.de).
