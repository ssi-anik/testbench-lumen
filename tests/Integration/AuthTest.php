<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\Helper;
use App\Providers\AuthServiceProvider;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Factory;
use Laravel\Lumen\Routing\Router;

class AuthTest extends TestCase
{
    use Helper;

    protected function withEloquent(): bool
    {
        return true;
    }

    protected function serviceProviders(): array
    {
        return [AuthServiceProvider::class];
    }

    protected function dontReportExceptions(): array
    {
        return [AuthenticationException::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->routeMiddleware(['auth' => Authenticate::class]);
    }

    protected function routes(Router $router): void
    {
        $router->group(['middleware' => 'auth'], function ($router) {
            $router->get('guarded', function () {
                return response()->json(['user' => app(Factory::class)->user()]);
            });
        });

        $router->get('unguarded', function () {
            return response()->json(['user' => app(Factory::class)->user()]);
        });
    }

    public function testCanActAsAUser()
    {
        $user = $this->runMigration()->createUser();

        $this->get('/guarded')->seeStatusCode(500);

        $response = json_decode($this->get('unguarded')->seeStatusCode(200)->response->getContent(), true);
        $this->assertTrue(is_null($response['user']));

        $this->actingAs($user)->get('/guarded')->seeStatusCode(200);
    }

    /*public function testDependencyInjectionIsWorkingCorrectly()
    {
        $this->call('GET', 'test', ['name' => 'testbench-lumen', 'env' => 'testing']);
        $response = $this->response->getData(true);

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('name', $response['data']);
        $this->assertArrayHasKey('env', $response['data']);
        $this->assertTrue('testing' == $response['data']['env']);
        $this->assertTrue('testbench-lumen' == $response['data']['name']);
    }*/
}
