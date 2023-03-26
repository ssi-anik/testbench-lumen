<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\Helper;
use Throwable;

class EloquentEnabledTest extends TestCase
{
    use Helper;

    protected function withEloquent(): bool
    {
        return true;
    }

    public function testEloquentIsLoaded()
    {
        $this->runMigration();

        $user = $this->createUser();
        $this->assertTrue(1 === $user->id);
        $this->seeInDatabase('users', ['id' => 1]);
    }
}
