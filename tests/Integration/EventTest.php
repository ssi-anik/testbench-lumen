<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use App\Events\ExampleEvent;
use App\Providers\EventServiceProvider;

class EventTest extends TestCase
{
    protected function serviceProviders(): array
    {
        return [EventServiceProvider::class];
    }

    public function testFiredEventsCanBeListened()
    {
        $this->expectsEvents(ExampleEvent::class);
        $this->app['events']->dispatch(new ExampleEvent());

        $this->withoutEvents();
        $this->app['events']->dispatch(new ExampleEvent());
    }
}
