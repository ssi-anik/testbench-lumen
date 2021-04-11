<?php

declare(strict_types=1);

namespace Anik\Testbench\Concerns;

use App\Console\Kernel as ConsoleKernel;
use App\Exceptions\Handler;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Laravel\Lumen\Application;
use Laravel\Lumen\Routing\Router;

trait CreateApplication
{
    protected function withFacade(): bool
    {
        return false;
    }

    protected function withEloquent(): bool
    {
        return false;
    }

    protected function serviceProviders(): array
    {
        return [];
    }

    protected function routes(Router $router): void
    {
        //
    }

    protected function createApplication(): Application
    {
        $this->loadEnvironments();
        $app = $this->resolveApplication();
        $this->loadWithFacade($app);
        $this->loadWithEloquent($app);
        $this->bindExceptionHandler($app);
        $this->bindConsoleKernel($app);
        $this->registerServiceProviders($app);
        $this->bindRouter($app);

        return $app;
    }

    protected function loadEnvironments()
    {
    }

    protected function resolveApplication(): Application
    {
        $path = $_ENV['APP_BASE_PATH'] ?? realpath(dirname(__DIR__) . '/../vendor/laravel/lumen');

        return $app = new Application($path);
    }

    final protected function loadWithFacade(Application $app): void
    {
        if (false === $this->withFacade()) {
            return;
        }

        $app->withFacades();
    }

    final protected function loadWithEloquent(Application $app): void
    {
        if (false === $this->withEloquent()) {
            return;
        }

        $app->withEloquent();
    }

    final protected function bindExceptionHandler(Application $app): void
    {
        $app->singleton(ExceptionHandler::class, Handler::class);
    }

    final protected function bindConsoleKernel(Application $app): void
    {
        $app->singleton(Kernel::class, ConsoleKernel::class);
    }

    final protected function registerServiceProviders(Application $app): void
    {
        foreach ($this->serviceProviders() as $provider) {
            $app->register($provider);
        }
    }

    final protected function bindRouter(Application $app)
    {
        $app->router->get('/', function () use ($app) {
            return $app->version();
        });

        $this->routes($app->router);
    }
}
