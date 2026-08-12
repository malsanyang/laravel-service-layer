<?php

namespace MalSanyang\ServiceLayer\Support;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class ServiceLayerDiscoverer
{
    /**
     * Discover all service & contract classes
     *
     * @return array<class-string, class-string>
     */
    public static function discover(): array
    {
        $contractsPath = (string) config('service-layer.contracts_path', app_path('Services/Contracts'));

        if (! is_dir($contractsPath)) {
            return [];
        }

        $bindings = [];

        foreach (self::phpFiles($contractsPath) as $file) {
            $contract = self::classFromFile($file->getPathname(), $contractsPath);
            $service = self::serviceForContract($contract);

            if (interface_exists($contract) && class_exists($service)) {
                $bindings[$contract] = $service;
            }
        }

        ksort($bindings);

        return $bindings;
    }

    /**
     * Find php classes and interfaces
     *
     * @param  string  $path
     * @return iterable<SplFileInfo>
     */
    private static function phpFiles(string $path): iterable
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
                yield $file;
            }
        }
    }

    /**
     * Get class from file
     *
     * @param  string  $file
     * @param  string  $basePath
     * @return string
     */
    private static function classFromFile(string $file, string $basePath): string
    {
        $relative = trim(str_replace($basePath, '', $file), DIRECTORY_SEPARATOR);
        $relative = substr($relative, 0, -4); // remove .php

        return trim((string) config('service-layer.contract_namespace', 'App\\Services\\Contracts'), '\\')
            .'\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $relative);
    }

    /**
     * Get service from contract
     *
     * @param  string  $contract
     * @return string
     */
    private static function serviceForContract(string $contract): string
    {
        $contractNamespace = trim((string) config('service-layer.contract_namespace', 'App\\Services\\Contracts'), '\\');
        $serviceNamespace = trim((string) config('service-layer.service_namespace', 'App\\Services'), '\\');
        $contractSuffix = (string) config('service-layer.contract_suffix', 'Contract');

        $relative = str_starts_with($contract, $contractNamespace.'\\')
            ? substr($contract, strlen($contractNamespace) + 1)
            : class_basename($contract);

        if ($contractSuffix !== '' && str_ends_with($relative, $contractSuffix)) {
            $relative = substr($relative, 0, -strlen($contractSuffix));
        }

        return $serviceNamespace.'\\'.$relative;
    }
}
