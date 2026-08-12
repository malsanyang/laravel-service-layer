<?php

namespace MalSanyang\ServiceLayer\Console\Commands;

use Illuminate\Console\Command;

class ServiceLayerInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service-layer:install {--force : Overwrite existing published files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the service layer package resources';

    /**
     * Execute the console command
     *
     * @return int
     */
    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'service-layer-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'service-layer-stubs',
            '--force' => $this->option('force'),
        ]);

        $this->ensureBaseServiceExists();

        $this->components->info('Service layer installed successfully.');

        return self::SUCCESS;
    }

    /**
     * Create BaseService if it does not exist
     */
    private function ensureBaseServiceExists(): void
    {
        $path = app_path('Services/BaseService.php');

        if (file_exists($path) && ! $this->option('force')) {
            return;
        }

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, str_replace(
            '{{ namespace }}',
            trim(app()->getNamespace(), '\\').'\\Services',
            file_get_contents(__DIR__.'/../../../stubs/base-service.stub')
        ));
    }
}
