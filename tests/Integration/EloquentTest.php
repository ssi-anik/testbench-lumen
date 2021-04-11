<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use App\Models\User;
use Throwable;

class EloquentTest extends TestCase
{
    protected static $LOAD_ELOQUENT = false;

    protected function withEloquent(): bool
    {
        // value gets changed in the following methods
        return static::$LOAD_ELOQUENT;
    }

    protected function runMigration()
    {
        $this->artisan('migrate:fresh', [
            '--path' => realpath(dirname(__DIR__) . '/Migrations'),
            '--realpath' => true,
        ]);
    }

    public function testEloquentIsNotLoaded()
    {
        // For the following test, it'll load Eloquent.
        static::$LOAD_ELOQUENT = true;

        $this->expectException(Throwable::class);
        User::create(['name' => 'testbench-lumen']);
    }

    public function testEloquentIsLoaded()
    {
        $this->runMigration();
        $user = (new User())->fill([
            'name' => 'testbench-lumen',
            'email' => 'testbench.lumen@example.com',
        ]);
        $user->password = app('hash')->make('12345');
        $user->save();

        $this->assertTrue(1 === $user->id);

        // reverting to original state
        static::$LOAD_ELOQUENT = false;
    }

    public function testEloquentCanBeLoadedUsingAppInstance()
    {
        $this->app->withEloquent();

        $this->runMigration();

        $user = (new User())->fill([
            'name' => 'testbench-lumen',
            'email' => 'testbench.lumen@example.com',
        ]);
        $user->password = app('hash')->make('12345');
        $user->save();

        $this->assertTrue(1 === $user->id);
    }
}
