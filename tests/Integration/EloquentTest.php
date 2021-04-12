<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\Helper;
use Throwable;

class EloquentTest extends TestCase
{
    use Helper;

    protected static $LOAD_ELOQUENT = false;

    protected function withEloquent(): bool
    {
        // value gets changed in the following methods
        return static::$LOAD_ELOQUENT;
    }

    public function testEloquentIsNotLoaded()
    {
        // For the following test, it'll load Eloquent.
        static::$LOAD_ELOQUENT = true;

        $this->expectException(Throwable::class);
        $class = $this->getEloquentModel();
        $class::create(['name' => 'testbench-lumen']);
    }

    public function testEloquentIsLoaded()
    {
        $this->runMigration();

        $user = $this->createUser();
        $this->assertTrue(1 === $user->id);

        // reverting to original state
        static::$LOAD_ELOQUENT = false;
    }

    public function testEloquentCanBeLoadedUsingAppInstance()
    {
        $this->app->withEloquent();

        $this->runMigration();

        $user = $this->createUser();

        $this->assertTrue(1 === $user->id);
    }
}
