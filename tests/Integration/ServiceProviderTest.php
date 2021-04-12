<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\DontUseThisTestServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function serviceProviders(): array
    {
        return [DontUseThisTestServiceProvider::class];
    }

    public function testServiceProviderShouldBeBound()
    {
        $this->assertTrue($this->app->bound(DontUseThisTestServiceProvider::BIND_NAME));
        $this->assertTrue($this->app->make(DontUseThisTestServiceProvider::BIND_NAME) == DontUseThisTestServiceProvider::BOUND_VALUE);
    }
}
