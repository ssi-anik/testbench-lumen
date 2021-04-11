<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\DontUseThisTestableServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function serviceProviders(): array
    {
        return [DontUseThisTestableServiceProvider::class];
    }

    public function testServiceProviderShouldBeBound()
    {
        $this->assertTrue($this->app->bound(DontUseThisTestableServiceProvider::BIND_NAME));
        $this->assertTrue($this->app->make(DontUseThisTestableServiceProvider::BIND_NAME) == DontUseThisTestableServiceProvider::BOUND_VALUE);
    }
}
