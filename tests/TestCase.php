<?php

namespace MalSanyang\ServiceLayer\Tests;

use MalSanyang\ServiceLayer\ServiceLayerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Get package providers
     *
     * @param  $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [ServiceLayerServiceProvider::class];
    }
}
