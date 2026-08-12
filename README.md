# Laravel Service Layer

A Laravel package for generating service classes and service contracts, with automatic convention-based binding and optional cached bindings for production.

## Installation

```bash
composer require malsanyang/laravel-service-layer
php artisan service-layer:install
```

Laravel auto-discovers the package service provider.

## Usage

Generate a service and matching contract:

```bash
php artisan service-layer:make-service UserService
```

This creates:

```text
app/Services/UserService.php
app/Services/Contracts/UserServiceContract.php
```

Nested services are supported:

```bash
php artisan service-layer:make-service Billing/InvoiceService
```

This creates:

```text
app/Services/Billing/InvoiceService.php
app/Services/Contracts/Billing/InvoiceServiceContract.php
```

## Automatic binding

The package automatically binds contracts to services by convention:

```php
App\Services\Contracts\UserServiceContract::class => App\Services\UserService::class
```

and:

```php
App\Services\Contracts\Billing\InvoiceServiceContract::class => App\Services\Billing\InvoiceService::class
```

No application service provider editing is required.

## Production cache

For production, cache discovered bindings:

```bash
php artisan service-layer:cache
```

Clear the cache:

```bash
php artisan service-layer:clear
```

The cache file is written to:

```text
bootstrap/cache/service-layer.php
```

## Configuration

Publish the config:

```bash
php artisan vendor:publish --tag=service-layer-config
```

Default config:

```php
return [
    'services_path' => app_path('Services'),
    'contracts_path' => app_path('Services/Contracts'),
    'service_namespace' => 'App\\Services',
    'contract_namespace' => 'App\\Services\\Contracts',
    'contract_suffix' => 'Contract',
    'base_service' => 'App\\Services\\BaseService',
    'cache_path' => base_path('bootstrap/cache/service-layer.php'),
];
```

## Custom stubs

Publish stubs:

```bash
php artisan vendor:publish --tag=service-layer-stubs
```

They will be published to:

```text
stubs/vendor/service-layer
```

## Commands

```bash
php artisan service-layer:make-service UserService
php artisan service-layer:make-contract UserServiceContract
php artisan service-layer:install
php artisan service-layer:cache
php artisan service-layer:clear
```

## Testing

```bash
composer test
```

## License

MIT.
