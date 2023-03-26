<?php

declare(strict_types=1);

namespace Anik\Testbench;

use Anik\Testbench\Concerns\Auth;
use Anik\Testbench\Concerns\Console;
use Anik\Testbench\Concerns\Database;
use Anik\Testbench\Concerns\Event;
use Anik\Testbench\Concerns\Job;
use Anik\Testbench\Concerns\Testing;
use Laravel\Lumen\Testing\Concerns\MakesHttpRequests;
use PHPUnit\Framework\TestCase as PHPUnit;

abstract class TestCase extends PHPUnit
{
    use Auth;
    use Console;
    use Database;
    use Event;
    use Job;
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

        $this->runThroughAnnotations('setup-before', $this->app);
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
