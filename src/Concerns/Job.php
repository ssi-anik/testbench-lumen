<?php

declare(strict_types=1);

namespace Anik\Testbench\Concerns;

use Mockery;

trait Job
{
    /**
     * Specify a list of jobs that should be dispatched for the given operation.
     *
     * These jobs will be mocked, so that handlers will not actually be executed.
     *
     * @param array|string $jobs
     *
     * @return $this
     */
    protected function expectsJobs($jobs): self
    {
        $jobs = is_array($jobs) ? $jobs : func_get_args();

        $mock = Mockery::mock('Illuminate\Bus\Dispatcher[dispatch]', [$this->app]);

        foreach ($jobs as $job) {
            $mock->shouldReceive('dispatch')->atLeast()->once()->with(Mockery::type($job));
        }

        $this->app->instance(\Illuminate\Contracts\Bus\Dispatcher::class, $mock);

        return $this;
    }

    /**
     * Mock the job dispatcher so all jobs are silenced and collected.
     *
     * @return $this
     */
    protected function withoutJobs()
    {
        $mock = Mockery::mock('Illuminate\Bus\Dispatcher[dispatch]', [$this->app]);

        $mock->shouldReceive('dispatch')->andReturnUsing(function ($dispatched) {
            $this->dispatchedJobs[] = $dispatched;
        });

        $this->app->instance(\Illuminate\Contracts\Bus\Dispatcher::class, $mock);

        return $this;
    }
}
