<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Laravel\Lumen\Application;
use Mockery;

class ApplicationCallbacksTest extends TestCase
{
    protected function setUp(): void
    {
        $afterCreated = Mockery::mock()->shouldReceive('__invoke')->with(Mockery::on(function ($app) {
            return $app instanceof Application;
        }))->times(1)->getMock();
        $this->afterApplicationCreated([$afterCreated, '__invoke']);

        $afterRefreshed = Mockery::mock()->shouldReceive('__invoke')->withNoArgs()->times(1)->getMock();
        $this->afterApplicationDestroyed([$afterRefreshed, '__invoke']);

        parent::setUp();
    }

    public function testAllTheCallbacksAreCalled()
    {
        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        $beforeDestroy = Mockery::mock()->shouldReceive('__invoke')->with(Mockery::on(function ($app) {
            return $app instanceof Application;
        }))->times(1)->getMock();
        $this->beforeApplicationDestroyed([$beforeDestroy, '__invoke']);

        $afterDestroy = Mockery::mock()->shouldReceive('__invoke')->withNoArgs()->times(1)->getMock();
        $this->afterApplicationDestroyed([$afterDestroy, '__invoke']);

        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}