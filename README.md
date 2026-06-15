# Tankerkoenig PHP Client

A PHP library for the [Tankerkoenig API](https://creativecommons.tankerkoenig.de) built with Clean
Architecture principles.

The library is HTTP client, Cache, and Logger agnostic — bring your own PSR-compatible
implementations.

## Requirements

- PHP 8.2+
- PSR-18 HTTP Client implementation
- PSR-17 HTTP Factory implementation
- PSR-16 Simple Cache implementation

## Installation

```bash
git clone https://github.com/dudochnicht/tankerkoenig-api.git
cd tankerkoenig
composer install
```

Then install a PSR-18 compatible HTTP client and a PSR-16 cache provider of your choice:

### Symfony Component Suite (Recommended)

```bash
composer require symfony/http-client symfony/cache nyholm/psr7
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

Depending on your project setup, you can either let your Framework handle the dependency graph
automatically (Recommended) or use the built-in Factory for Plain PHP.

### Modern Frameworks (Symfony, Laravel, etc.)

Because this library strictly follows Dependency Inversion and Constructor Injection, modern DI
containers will automatically resolve all nested dependencies (UseCases, Repositories, Mappers) via
Autowiring. You do not need the Factory.

### Symfony Setup (config/services.yaml)

Just register the Configuration DTO. Symfony handles the rest, including mapping the interface:

```yaml
services:
  _defaults:
    autowire: true
    autoconfigure: true

  App\Tankerkoenig\TankerkoenigConfig:
    arguments:
      $apiKey: '%env(TANKERKOENIG_API_KEY)%'
      $baseUrl: '%env(TANKERKOENIG_BASE_URL)%'
      $debug: '%env(bool:TANKERKOENIG_DEBUG_MODE)%'

  App\Tankerkoenig\TankerkoenigClientInterface: '@App\Tankerkoenig\TankerkoenigClient'
```

Now simply type-hint `TankerkoenigClientInterface` anywhere (e.g., in your Controllers or Services).

### Plain PHP (Using the Factory)

```php
use App\Tankerkoenig\TankerkoenigClientFactory;
use App\Tankerkoenig\TankerkoenigConfig;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\HttpClient\Psr18Client;

$factory    = new Psr17Factory();
$httpClient = new Psr18Client(null, $factory, $factory);

$client = TankerkoenigClientFactory::create(
    httpClient     : $httpClient,
    requestFactory : $factory,
    config         : new TankerkoenigConfig(
        apiKey : $_ENV['TANKERKOENIG_API_KEY'],
        baseUrl: $_ENV['TANKERKOENIG_BASE_URL'],
        debug  : (bool) $_ENV['TANKERKOENIG_DEBUG_MODE'],
    ),
);
```

## Advanced Features

### Caching (PSR-16 Decorator)

Since fuel prices do not change every second, you should wrap your client inside the
`CachingTankerkoenigClient` decorator. It uses the structural Decorator Pattern so you can intercept
requests transparently.

```php
use App\Tankerkoenig\CachingTankerkoenigClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

$psr16Cache = new Psr16Cache(
    new FilesystemAdapter(namespace: 'tankerkoenig', defaultLifetime: 300)
);

// Wrap your existing client instance (Works with both Approach A & B)
$client = new CachingTankerkoenigClient(
    inner: $client, // The base TankerkoenigClient
    cache: $psr16Cache,
    ttl  : 300
);
```

### Logging (PSR-3)

Pass any PSR-3 compatible logger (like Monolog) into the Factory or let your framework wire it
automatically:

```bash
composer require monolog/monolog
```

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

$logger = new Logger('tankerkoenig');
$logger->pushHandler(new StreamHandler('/var/logs/tankerkoenig.log', Level::Debug));

// Pass to factory via the `logger:` parameter
```

# API Examples

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
    sort  : SortBy::PRICE,
);

$response = $client->getList($request);

print_r($response->getStations());
```

## Gas Station Detail

```php
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;

$request  = new GetGasStationDetailRequest(
    id: '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8'
);

$response = $client->getDetail($request);

print_r($response->getStation());
```

## Gas Station Prices

```php
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesRequest;

$request = new GetGasStationPricesRequest(ids: [
    '4429a7d9-fb2d-4c29-8cfe-2ca90323f9f8',
    '446bdcf5-9f75-47fc-9cfa-2c3d6fda1c3b',
]);

$response = $client->getPrices($request);

print_r($response->getPrices());
```

## Error Handling

```php
use App\Tankerkoenig\Application\Exception\InvalidRequestException;
use App\Tankerkoenig\Domain\Exception\TankerkoenigException;

try {
    $response = $client->getList($request);

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
