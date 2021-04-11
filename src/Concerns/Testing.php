<?php

declare(strict_types=1);

namespace Anik\Testbench\Concerns;

use Illuminate\Support\Facades\Facade;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutEvents;
use Laravel\Lumen\Testing\WithoutMiddleware;
use Mockery;

trait Testing
{
    protected $afterApplicationCreatedCallbacks = [];
    protected $afterApplicationRefreshedCallbacks = [];
    protected $beforeApplicationDestroyedCallbacks = [];
    protected $afterApplicationDestroyedCallbacks = [];
    protected $hasSetUpRun = false;

    protected function fireCallbacks($callbacks, ...$parameters): void
    {
        foreach ($callbacks as $callback) {
            call_user_func_array($callback, $parameters);
        }
    }

    protected function fireAfterApplicationCreated(): void
    {
        $this->fireCallbacks($this->afterApplicationCreatedCallbacks, $this->app);
    }

    protected function fireAfterApplicationRefreshed(): void
    {
        $this->fireCallbacks($this->afterApplicationRefreshedCallbacks, $this->app);
    }

    protected function fireBeforeApplicationDestroyed(): void
    {
        $this->fireCallbacks($this->beforeApplicationDestroyedCallbacks, $this->app);
    }

    protected function fireAfterApplicationDestroyed(): void
    {
        $this->fireCallbacks($this->afterApplicationDestroyedCallbacks);
    }

    public function afterApplicationCreated(callable $callback): void
    {
        $this->afterApplicationCreatedCallbacks[] = $callback;

        if ($this->hasSetUpRun) {
            call_user_func_array($callback, [$this->app]);
        }
    }

    public function afterApplicationRefreshed(callable $callback): void
    {
        $this->afterApplicationRefreshedCallbacks[] = $callback;

        if ($this->hasSetUpRun) {
            call_user_func_array($callback, [$this->app]);
        }
    }

    public function beforeApplicationDestroyed(callable $callback): void
    {
        $this->beforeApplicationDestroyedCallbacks[] = $callback;
    }

    public function afterApplicationDestroyed(callable $callback): void
    {
        $this->afterApplicationDestroyedCallbacks[] = $callback;
    }

    final public function setUpTestEnvironment(): void
    {
        if (!$this->app) {
            $this->refreshApplication();
        }

        $this->fireAfterApplicationRefreshed();

        $this->setUpTraits();

        $this->fireAfterApplicationCreated();

        $this->hasSetUpRun = true;
    }

    final public function tearDownTestEnvironment(): void
    {
        if ($this->app) {
            $this->fireBeforeApplicationDestroyed();

            $this->app->flush();
            $this->app = null;
        }

        $this->hasSetUpRun = false;

        $this->afterApplicationCreatedCallbacks = [];
        $this->afterApplicationRefreshedCallbacks = [];
        $this->beforeApplicationDestroyedCallbacks = [];

        $this->fireAfterApplicationDestroyed();

        $this->afterApplicationDestroyedCallbacks = [];

        if (class_exists(Mockery::class)) {
            if (($container = Mockery::getContainer()) !== null) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            Mockery::close();
        }
    }

    protected function refreshApplication(): void
    {
        if ($this->withFacade()) {
            Facade::clearResolvedInstances();
        }

        $this->app = $this->createApplication();

        $url = $this->app->make('config')->get('app.url', 'http://localhost');

        $this->app->make('url')->forceRootUrl($url);

        $this->app->boot();
    }

    protected function reloadApplication(): void
    {
        $this->tearDownTestEnvironment();
        $this->setUpTestEnvironment();
    }

    protected function setUpTraits(): void
    {
        $uses = array_flip(class_uses_recursive(get_class($this)));

        if (isset($uses[DatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }

        if (isset($uses[DatabaseTransactions::class])) {
            $this->beginDatabaseTransaction();
        }

        if (isset($uses[WithoutMiddleware::class])) {
            $this->disableMiddlewareForAllTests();
        }

        if (isset($uses[WithoutEvents::class])) {
            $this->disableEventsForAllTests();
        }
    }
}
