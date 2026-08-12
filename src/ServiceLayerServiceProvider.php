<?php

namespace MalSanyang\ServiceLayer;

use Illuminate\Support\ServiceProvider;
use MalSanyang\ServiceLayer\Console\Commands\MakeServiceCommand;
use MalSanyang\ServiceLayer\Console\Commands\MakeServiceContractCommand;
use MalSanyang\ServiceLayer\Console\Commands\ServiceLayerCacheCommand;
use MalSanyang\ServiceLayer\Console\Commands\ServiceLayerClearCommand;
use MalSanyang\ServiceLayer\Console\Commands\ServiceLayerInstallCommand;
use MalSanyang\ServiceLayer\Support\ServiceLayerDiscoverer;

class ServiceLayerServiceProvider extends ServiceProvider
{
    /**
     * Register service layer service
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/service-layer.php', 'service-layer');

        foreach ($this->bindings() as $contract => $service) {
            $this->app->bind($contract, $service);
        }
    }

    /**
     * Bootstrap service layer service
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeServiceCommand::class,
                MakeServiceContractCommand::class,
                ServiceLayerCacheCommand::class,
                ServiceLayerClearCommand::class,
                ServiceLayerInstallCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/service-layer.php' => config_path('service-layer.php'),
            ], 'service-layer-config');

            $this->publishes([
                __DIR__.'/../stubs' => base_path('stubs/vendor/service-layer'),
            ], 'service-layer-stubs');
        }
    }

    /**
     * Bind service contract to services
     *
     * @return array<class-string, class-string>
     */
    protected function bindings(): array
    {
        $cachePath = config('service-layer.cache_path');

        if (is_string($cachePath) && file_exists($cachePath)) {
            return require $cachePath;
        }

        return ServiceLayerDiscoverer::discover();
    }
}
