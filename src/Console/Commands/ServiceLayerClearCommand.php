<?php

namespace MalSanyang\ServiceLayer\Console\Commands;

use Illuminate\Console\Command;

class ServiceLayerClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service-layer:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached service layer bindings';

    /**
     * Execute the console command
     *
     * @return int
     */
    public function handle(): int
    {
        $cachePath = (string) config('service-layer.cache_path', base_path('bootstrap/cache/service-layer.php'));

        if (file_exists($cachePath)) {
            unlink($cachePath);
        }

        $this->components->info('Service layer cache cleared successfully.');

        return self::SUCCESS;
    }
}
