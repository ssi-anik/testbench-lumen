<?php

declare(strict_types=1);

namespace Anik\Testbench\Concerns;

trait Database
{
    /**
     * Assert that a given where condition exists in the database.
     *
     * @param string $table
     * @param array $data
     * @param string|null $onConnection
     *
     * @return \Laravel\Lumen\Testing\TestCase
     */
    protected function seeInDatabase(string $table, array $data, $onConnection = null)
    {
        $count = $this->app->make('db')->connection($onConnection)->table($table)->where($data)->count();

        $this->assertGreaterThan(
            0,
            $count,
            sprintf(
                'Unable to find row in database table [%s] that matched attributes [%s].',
                $table,
                json_encode($data)
            )
        );

        return $this;
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param string $table
     * @param array $data
     * @param string|null $onConnection
     *
     * @return $this
     */
    protected function missingFromDatabase(string $table, array $data, $onConnection = null)
    {
        return $this->notSeeInDatabase($table, $data, $onConnection);
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param string $table
     * @param array $data
     * @param string|null $onConnection
     *
     * @return $this
     */
    protected function notSeeInDatabase(string $table, array $data, $onConnection = null)
    {
        $count = $this->app->make('db')->connection($onConnection)->table($table)->where($data)->count();

        $this->assertEquals(
            0,
            $count,
            sprintf(
                'Found unexpected records in database table [%s] that matched attributes [%s].',
                $table,
                json_encode($data)
            )
        );

        return $this;
    }
}
