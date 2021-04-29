<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use App\Console\Kernel;

class ArtisanTest extends TestCase
{
    public function testArtisanCallRunsOkay()
    {
        $this->assertTrue($this->artisan() instanceof Kernel);
        $this->assertTrue(0 === $this->artisan('help'));
        $this->assertTrue(0 === $this->artisan('list'));
    }

    public function testArtisanOutput()
    {
        $this->artisan('cache:clear');
        $this->assertStringContainsString('Application cache cleared', $this->artisanOutput());
    }

    public function testConsoleInstance()
    {
        $this->assertTrue($this->console() instanceof Kernel);
        $returned = $this->console()->call('cache:clear');
        $this->assertEquals(0, $returned);
        $this->assertStringContainsString('Application cache cleared', $this->console()->output());
    }
}
