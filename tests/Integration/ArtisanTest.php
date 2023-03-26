<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use App\Console\Kernel;

class ArtisanTest extends TestCase
{
    public function testArtisanCallRunsOkay()
    {
        $this->assertInstanceOf(Kernel::class, $this->artisan());
        $this->assertEquals(0, $this->artisan('help'));
        $this->assertEquals(0, $this->artisan('list'));
    }

    public function testArtisanOutput()
    {
        $this->artisan('cache:clear');
        $this->assertStringContainsString('Application cache cleared', $this->artisanOutput());
    }

    public function testConsoleInstance()
    {
        $this->assertInstanceOf(Kernel::class, $this->console());
        $returned = $this->console()->call('cache:clear');
        $this->assertEquals(0, $returned);
        $this->assertStringContainsString('Application cache cleared', $this->console()->output());
    }
}
