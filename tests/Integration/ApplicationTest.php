<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Laravel\Lumen\Application;

class ApplicationTest extends TestCase
{
    public function testAppPropertyInstanceOfLumenApplication()
    {
        $this->assertTrue($this->app instanceof Application);
    }

    public function testReloadApplication()
    {
        $this->app->bind('should-exists', function () {
            return 'does-exist';
        });
        $this->assertTrue($this->app->make('should-exists') == 'does-exist');

        $this->reloadApplication();
        $this->expectException(BindingResolutionException::class);
        $this->app->make('should-exist');
    }
}
