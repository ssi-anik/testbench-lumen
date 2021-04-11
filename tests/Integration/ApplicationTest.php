<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Laravel\Lumen\Application;

class ApplicationTest extends TestCase
{
    public function testAppPropertyInstanceOfLumenApplication()
    {
        $this->assertTrue($this->app instanceof Application);
    }
}
