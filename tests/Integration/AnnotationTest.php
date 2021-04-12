<?php

namespace Anik\Testbench\Tests\Integration;

use Anik\Testbench\TestCase;
use Laravel\Lumen\Application;

class AnnotationTest extends TestCase
{
    protected function firstCalled(Application $app)
    {
        $app->bind('value-should-be-found', function () {
            return 'as-is';
        });
    }

    protected function secondCalled(Application $app)
    {
        $app->bind('value-should-be-found', function () {
            return 'modified';
        });
    }

    /**
     * @setup-before firstCalled
     */
    public function testAnnotationFirstOnly()
    {
        $this->assertTrue('as-is' === $this->app->make('value-should-be-found'));
    }

    /**
     * @setup-before firstCalled
     * @setup-before secondCalled
     */
    public function testMultipleAnnotations()
    {
        $this->assertTrue('modified' === $this->app->make('value-should-be-found'));
    }
}
