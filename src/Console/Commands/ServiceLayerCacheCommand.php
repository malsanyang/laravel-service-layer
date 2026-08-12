<?php

namespace MalSanyang\ServiceLayer\Console\Commands;

use Illuminate\Console\Command;
use MalSanyang\ServiceLayer\Support\ServiceLayerDiscoverer;

class ServiceLayerCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service-layer:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache discovered service layer bindings';

    /**
     * Execute the console command
     *
     * @return int
     */
    public function handle(): int
    {
        $bindings = ServiceLayerDiscoverer::discover();
        $cachePath = (string) config('service-layer.cache_path', base_path('bootstrap/cache/service-layer.php'));

        if (! is_dir(dirname($cachePath))) {
            mkdir(dirname($cachePath), 0755, true);
        }

        file_put_contents($cachePath, "<?php\n\nreturn ".var_export($bindings, true).";\n");

        $this->components->info('Service layer bindings cached successfully.');
        $this->line(count($bindings).' binding(s) cached.');

        return self::SUCCESS;
    }
}
