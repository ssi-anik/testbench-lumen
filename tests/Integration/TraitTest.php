<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use App\Providers\AuthServiceProvider;
use Illuminate\Contracts\Auth\Factory;
use Laravel\Lumen\Routing\Router;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutEvents;
use Laravel\Lumen\Testing\WithoutMiddleware;

class TraitTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;
    use WithoutEvents;

    protected function serviceProviders(): array
    {
        return [AuthServiceProvider::class];
    }

    protected function withEloquent(): bool
    {
        return true;
    }

    protected function routes(Router $router): void
    {
        $router->group(['middleware' => 'auth'], function ($router) {
            $router->get('guarded', function () {
                return response()->json(['user' => app(Factory::class)->user() ?? 'middleware bypassed']);
            });
        });
    }

    public function testWithoutMiddleware()
    {
        $response = json_decode($this->get('/guarded')->seeStatusCode(200)->response->getContent(), true);
        $this->assertTrue($response['user'] == 'middleware bypassed');
    }
}
