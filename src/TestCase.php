<?php

declare(strict_types=1);

namespace Anik\Testbench;

use Anik\Testbench\Concerns\Auth;
use Anik\Testbench\Concerns\Console;
use Anik\Testbench\Concerns\CreateApplication;
use Anik\Testbench\Concerns\Database;
use Anik\Testbench\Concerns\Event;
use Anik\Testbench\Concerns\Testing;
use Laravel\Lumen\Testing\Concerns\MakesHttpRequests;
use PHPUnit\Framework\TestCase as PHPUnit;

abstract class TestCase extends PHPUnit
{
    use Auth;
    use Console;
    use CreateApplication;
    use Database;
    use Event;
    use MakesHttpRequests;
    use Testing;

    /**
     * The application instance.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->setUpTestEnvironment();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tearDownTestEnvironment();
    }
}
