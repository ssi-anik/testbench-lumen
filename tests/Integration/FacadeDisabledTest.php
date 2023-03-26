<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Exception;
use Illuminate\Support\Facades\App;

class FacadeDisabledTest extends TestCase
{
    protected function withFacade(): bool
    {
        return false;
    }

    public function testFacadeIsNotLoaded()
    {
        $this->expectException(Exception::class);
        App::environment();
    }

    public function testFacadeCanBeLoadedUsingAppInstance()
    {
        $this->app->withFacades();
        $this->assertTrue('testing' === App::environment());
    }
}
