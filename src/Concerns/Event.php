<?php

declare(strict_types=1);

namespace Anik\Testbench\Concerns;

use Illuminate\Contracts\Events\Dispatcher;
use Mockery;

trait Event
{
    /**
     * Specify a list of events that should be fired for the given operation.
     *
     * These events will be mocked, so that handlers will not actually be executed.
     *
     * @param array|string $events
     *
     * @return $this
     */
    public function expectsEvents($events)
    {
        $events = is_array($events) ? $events : func_get_args();

        $mock = Mockery::spy(Dispatcher::class);

        $mock->shouldReceive('dispatch')->andReturnUsing(function ($called) use (&$events) {
            foreach ($events as $key => $event) {
                if (
                    (is_string($called) && $called === $event)
                    || (is_string($called) && is_subclass_of($called, $event))
                    || (is_object($called) && $called instanceof $event)
                ) {
                    unset($events[$key]);
                }
            }
        });

        $this->beforeApplicationDestroyed(function () use (&$events) {
            if ($events) {
                throw new Exception('The following events were not fired: [' . implode(', ', $events) . ']');
            }
        });

        $this->app->instance('events', $mock);

        return $this;
    }

    /**
     * Mock the event dispatcher so all events are silenced.
     *
     * @return $this
     */
    protected function withoutEvents()
    {
        $mock = Mockery::mock(Dispatcher::class);

        $mock->shouldReceive('dispatch');

        $this->app->instance('events', $mock);

        return $this;
    }
}
