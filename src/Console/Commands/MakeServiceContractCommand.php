<?php

namespace MalSanyang\ServiceLayer\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeServiceContractCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service-layer:make-contract {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service contract';

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
        return $rootNamespace.'\\Services\\Contracts';
    }

    /**
     * Resolve stub path
     *
     * @return string
     */
    private function resolveStubPath(): string
    {
        $published = base_path('stubs/vendor/service-layer/'.'service-contract.stub');

        return file_exists($published) ? $published : __DIR__.'/../../../stubs/'.'service-contract.stub';
    }
}
