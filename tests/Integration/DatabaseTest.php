<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Anik\Testbench\Tests\Extensions\Helper;
use Throwable;

class DatabaseTest extends TestCase
{
    use Helper;

    protected function withEloquent(): bool
    {
        return true;
    }

    public function testSeeInDatabase()
    {
        $this->runMigration()->createUser();
        $this->seeInDatabase('users', ['id' => 1]);
    }

    public function testNotSeeInDatabase()
    {
        $user = $this->runMigration()->createUser();
        $this->assertTrue($user->delete());
        $this->notSeeInDatabase('users', ['id' => $user->id]);
        $this->missingFromDatabase('users', ['id' => $user->id]);
    }
}
