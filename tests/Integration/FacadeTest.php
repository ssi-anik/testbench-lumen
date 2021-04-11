<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class FacadeTest extends TestCase
{
    protected static $LOAD_FACADE = false;

    protected function withFacade(): bool
    {
        // value gets changed in the following methods
        return static::$LOAD_FACADE;
    }

    public function testFacadeIsNotLoaded()
    {
        // For the following test, it'll load facade.
        static::$LOAD_FACADE = true;

        $this->expectException(\Exception::class);
        App::environment();
    }

    public function testFacadeIsLoaded()
    {
        $this->assertTrue('testing' === App::environment());

        // reverting to original state
        static::$LOAD_FACADE = false;
    }

    public function testFacadeCanBeLoadedUsingAppInstance()
    {
        $this->app->withFacades();
        $this->assertTrue('testing' === App::environment());
    }
}
