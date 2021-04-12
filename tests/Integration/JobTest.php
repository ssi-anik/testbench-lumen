<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\DontUseThisTestJob;
use Illuminate\Contracts\Bus\Dispatcher;

class JobTest extends TestCase
{
    public function testExpectsJob()
    {
        $this->expectsJobs(DontUseThisTestJob::class);
        $this->app[Dispatcher::class]->dispatch(new DontUseThisTestJob());

        $this->withoutJobs();
        $this->app[Dispatcher::class]->dispatch(new DontUseThisTestJob());
    }
}
