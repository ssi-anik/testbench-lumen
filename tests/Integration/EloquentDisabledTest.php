<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\Helper;
use Throwable;

class EloquentDisabledTest extends TestCase
{
    use Helper;

    protected function withEloquent(): bool
    {
        return false;
    }

    public function testEloquentIsNotLoaded()
    {
        $this->expectException(Throwable::class);
        $class = $this->getEloquentModel();
        $class::create(['name' => 'testbench-lumen']);
    }

    public function testEloquentCanBeLoadedUsingAppInstance()
    {
        $this->app->withEloquent();

        $this->runMigration();

        $user = $this->createUser();

        $this->assertEquals(1, $user->id);

        $this->seeInDatabase('users', ['id' => 1]);
    }
}
