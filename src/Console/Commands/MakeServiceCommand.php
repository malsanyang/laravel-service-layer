<?php

namespace MalSanyang\ServiceLayer\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Artisan;

class MakeServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service-layer:make-service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service and matching service contract';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        Artisan::call('service-layer:make-contract', [
            'name' => $this->argument('name').'Contract',
        ]);

        return parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath();
    }

    /**
     * Get default namespace
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Services';
    }

    /**
     * Build class from stub
     *
     * @param  string  $name
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        foreach ($this->stubVariables($name) as $variable => $value) {
            $stub = str_replace('{{ '.$variable.' }}', $value, $stub);
            $stub = str_replace('{{'.$variable.'}}', $value, $stub);
        }

        return $stub;
    }

    /**
     * Get stub variables
     *
     * @param  string  $name
     * @return array<string, string>
     */
    private function stubVariables(string $name): array
    {
        $class = class_basename($name);
        $contract = $class.'Contract';
        $folder = $this->folderNamespace();
        $root = trim($this->rootNamespace(), '\\');

        return [
            'baseService' => trim((string) config('service-layer.base_service', $root.'\\Services\\BaseService'), '\\'),
            'contract' => $contract,
            'contractFqn' => $root.'\\Services\\Contracts'.($folder !== '' ? '\\'.$folder : '').'\\'.$contract,
        ];
    }

    /**
     * Get namespace for needed service
     *
     * @return string
     */
    private function folderNamespace(): string
    {
        $parts = explode('/', (string) $this->argument('name'));
        array_pop($parts);

        return implode('\\', array_filter($parts));
    }

    /**
     * Resolve stub path
     *
     * @return string
     */
    private function resolveStubPath(): string
    {
        $published = base_path('stubs/vendor/service-layer/'.'service.stub');

        return file_exists($published) ? $published : __DIR__.'/../../../stubs/'.'service.stub';
    }
}
