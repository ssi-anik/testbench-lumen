<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Laravel\Lumen\Application;

class ApplicationTest extends TestCase
{
    public function testAppPropertyInstanceOfLumenApplication()
    {
        $this->assertInstanceOf(Application::class, $this->app);
    }

    public function testReloadApplication()
    {
        $this->app->bind('should-exists', function () {
            return 'does-exist';
        });

        $this->assertEquals('does-exist', $this->app->make('should-exists'));

        $this->reloadApplication();
        $this->expectException(BindingResolutionException::class);
        $this->app->make('should-exist');
    }
}
