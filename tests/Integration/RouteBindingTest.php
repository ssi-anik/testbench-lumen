<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;

class RouteBindingTest extends TestCase
{
    protected function routes(Router $router): void
    {
        $router->get('/test', function (Request $request) {
            return response()->json([
                'error' => false,
                'message' => 'I can be reached',
                'data' => $request->all(),
            ], 202);
        });
    }

    public function testDefaultRouteShouldBeAvailable()
    {
        $response = $this->get('/')->seeStatusCode(200)->response->getContent();
        $this->assertEquals($this->app->version(), $response);
    }

    public function testRouteCanBeBoundSuccessfully()
    {
        $this->json('GET', 'test')->seeStatusCode(202);
    }

    public function testDependencyInjectionIsWorkingCorrectly()
    {
        $this->call('GET', 'test', ['name' => 'testbench-lumen', 'env' => 'testing']);
        $response = $this->response->getData(true);

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('name', $response['data']);
        $this->assertArrayHasKey('env', $response['data']);
        $this->assertTrue('testing' == $response['data']['env']);
        $this->assertTrue('testbench-lumen' == $response['data']['name']);
    }
}
