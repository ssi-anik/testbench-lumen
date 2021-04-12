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

    protected function globalMiddlewares(Application $app): array
    {
        return [];
    }

    protected function routeMiddlewares(Application $app): array
    {
        return [];
    }

    protected function createApplication(): Application
    {
        $app = $this->resolveApplication();
        $this->loadWithFacade($app);
        $this->loadWithEloquent($app);
        $this->bindExceptionHandler($app);
        $this->bindConsoleKernel($app);
        $this->registerServiceProviders($app);
        $this->bindRouter($app);
        $this->registerMiddlewares($app);

        return $app;
    }

    final protected function registerMiddlewares(Application $app)
    {
        if ($routeMiddlewares = $this->routeMiddlewares($app)) {
            $app->routeMiddleware($routeMiddlewares);
        }

        if ($globalMiddlewares = $this->globalMiddlewares($app)) {
            $app->middleware($globalMiddlewares);
        }
    }

    protected function resolveApplication(): Application
    {
        $path = env('APP_BASE_PATH') ?? realpath(dirname(__DIR__) . '/../vendor/laravel/lumen');

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

    protected function dontReportExceptions(): array
    {
        return [];
    }

    final protected function bindExceptionHandler(Application $app): void
    {
        $app->singleton(ExceptionHandler::class, function () use ($app) {
            return new class ($this->dontReportExceptions()) extends Handler {
                public function __construct(array $dontReport = [])
                {
                    $this->dontReport = array_merge($this->dontReport, $dontReport);
                }
            };
        });
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
