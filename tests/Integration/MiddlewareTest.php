<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\DontUseThisTestMiddleware;
use Closure;
use Laravel\Lumen\Application;
use Laravel\Lumen\Routing\Router;
use Mockery;

class MiddlewareTest extends TestCase
{
    private $timesCalled = 2;

    protected function routeMiddlewares(Application $app): array
    {
        $mock = Mockery::spy(DontUseThisTestMiddleware::class);
        $mock->shouldReceive('handle')->times(1)->andReturnUsing(function ($request, $next) {
            --$this->timesCalled;

            return $next($request);
        });

        $app->instance(DontUseThisTestMiddleware::class, $mock);

        return ['test' => DontUseThisTestMiddleware::class];
    }

    protected function globalMiddlewares(Application $app): array
    {
        $mock = Mockery::mock();
        $mock->shouldReceive('handle')->times(1)->andReturnUsing(function ($request, Closure $next) {
            --$this->timesCalled;

            return $next($request);
        });

        return [[$mock, 'handle']];
    }

    protected function routes(Router $router): void
    {
        $router->group(['middleware' => 'test'], function ($router) {
            $router->get('middleware-test', function () {
                return response()->json([]);
            });
        });
    }

    public function testMiddlewaresAreCalled()
    {
        $this->get('/middleware-test')->seeStatusCode(200);
        $this->assertTrue(0 == $this->timesCalled);
    }
}
