<?php

namespace MalSanyang\ServiceLayer\Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use MalSanyang\ServiceLayer\Tests\TestCase;

class PackageCommandsTest extends TestCase
{
    public function test_service_layer_commands_are_registered(): void
    {
        $commands = array_keys($this->app->make(Kernel::class)->all());

        $this->assertContains('service-layer:make-service', $commands);
        $this->assertContains('service-layer:make-contract', $commands);
        $this->assertContains('service-layer:cache', $commands);
        $this->assertContains('service-layer:clear', $commands);
        $this->assertContains('service-layer:install', $commands);
    }
}
