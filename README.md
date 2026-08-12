# Laravel Service Layer

Laravel Service Layer provides Artisan generators for service classes and their
contracts, convention-based container bindings, customizable stubs, and an
optional binding cache for production.

## Requirements

- PHP `^8.3`
- Laravel 11, 12, or 13

## Installation

Install the package with Composer:

```bash
composer require malsanyang/laravel-service-layer
```

Laravel discovers the package service provider automatically. Run the installer
to publish the configuration and stubs and create the base service class:

```bash
php artisan service-layer:install
```

The installer creates or publishes:

```text
app/Services/BaseService.php
config/service-layer.php
stubs/vendor/service-layer/
```

Existing files are preserved. To overwrite them deliberately, use
`php artisan service-layer:install --force`.

## Generating services

Generate a service and its matching contract:

```bash
php artisan service-layer:make-service UserService
```

This creates:

```text
app/Services/UserService.php
app/Services/Contracts/UserServiceContract.php
```

The generated `UserService` extends `App\Services\BaseService` and implements
`UserServiceContract`.

Nested services retain the same directory structure on both sides:

```bash
php artisan service-layer:make-service Billing/InvoiceService
```

```text
app/Services/Billing/InvoiceService.php
app/Services/Contracts/Billing/InvoiceServiceContract.php
```

To generate only a contract, run:

```bash
php artisan service-layer:make-contract UserServiceContract
```

## Automatic contract binding

Contracts are scanned recursively and bound to concrete services by namespace,
relative path, and suffix. With the default configuration, these pairs are
discovered automatically:

```php
App\Services\Contracts\UserServiceContract::class
    => App\Services\UserService::class;

App\Services\Contracts\Billing\InvoiceServiceContract::class
    => App\Services\Billing\InvoiceService::class;
```

Both the interface and matching class must exist and be autoloadable. No manual
binding in an application service provider is required. You can type-hint a
contract normally:

```php
use App\Services\Contracts\UserServiceContract;

final class UserController
{
    public function __construct(
        private readonly UserServiceContract $users,
    ) {}
}
```

Bindings use Laravel's standard `bind` lifecycle, so they are not singletons.

## Binding cache

Without a cache, contracts are discovered when the package service provider is
registered. For production deployments, write the discovered map to the
configured cache file:

```bash
php artisan service-layer:cache
```

The default location is `bootstrap/cache/service-layer.php`. Rebuild the cache
after adding, removing, or renaming a service or contract. Clear it with:

```bash
php artisan service-layer:clear
```

## Configuration

The installer publishes `config/service-layer.php`. You can also publish it
independently:

```bash
php artisan vendor:publish --tag=service-layer-config
```

The available settings are:

| Setting | Default | Purpose |
| --- | --- | --- |
| `services_path` | `app_path('Services')` | Conventional service directory; concrete classes are resolved through `service_namespace`. |
| `contracts_path` | `app_path('Services/Contracts')` | Directory scanned recursively for contracts. |
| `service_namespace` | `App\Services` | Namespace used to resolve matching service classes. |
| `contract_namespace` | `App\Services\Contracts` | Namespace corresponding to `contracts_path`. |
| `contract_suffix` | `Contract` | Suffix removed when resolving a service name. |
| `base_service` | `App\Services\BaseService` | Base class imported by the service generator. |
| `cache_path` | `bootstrap/cache/service-layer.php` | File used to store cached bindings. |

The service generators currently create files under the conventional
`app/Services` and `app/Services/Contracts` namespaces. If you customize the
discovery paths or namespaces, manage the corresponding classes in those
locations and ensure they are covered by Composer autoloading.

## Customizing generated files

The installer publishes the package stubs automatically. To publish only the
stubs, run:

```bash
php artisan vendor:publish --tag=service-layer-stubs
```

Edit the files in `stubs/vendor/service-layer/` to customize newly generated
services and contracts. Existing generated classes are not changed.

## Commands

| Command | Description |
| --- | --- |
| `service-layer:install [--force]` | Publish package resources and create `BaseService`. |
| `service-layer:make-service {name}` | Generate a service and matching contract. |
| `service-layer:make-contract {name}` | Generate only a service contract. |
| `service-layer:cache` | Discover and cache contract-to-service bindings. |
| `service-layer:clear` | Remove the binding cache. |

## Development

Install development dependencies and run the full validation suite:

```bash
composer install
composer ci:check
```

The suite validates `composer.json`, checks formatting, runs Larastan and the
tests, and audits Composer dependencies. Individual commands are available as
`composer lint:check`, `composer analyse`, and `composer test`.

## Security

Please report vulnerabilities privately as described in the
[security policy](.github/SECURITY.md). Do not disclose security issues through public GitHub issues.

## License

Laravel Service Layer is open-source software licensed under the
[MIT License](LICENSE).
