<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Illuminate\Support\Facades\App;

class FacadeEnabledTest extends TestCase
{
    protected function withFacade(): bool
    {
        return true;
    }

    public function testFacadeIsLoaded()
    {
        $this->assertEquals('testing', App::environment());
    }
}
