<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;

class ArtisanTest extends TestCase
{
    public function testArtisanCallRunsOkay()
    {
        $this->assertTrue(0 === $this->artisan('help'));
        $this->assertTrue(0 === $this->artisan('list'));
    }
}
