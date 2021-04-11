<?php

declare(strict_types=1);

namespace Anik\Testbench\Concerns;

use Illuminate\Contracts\Console\Kernel;

trait Console
{
    /**
     * Call artisan command and return code.
     *
     * @param string $command
     * @param array $parameters
     *
     * @return int
     */
    public function artisan(string $command, $parameters = []): int
    {
        return $this->app[Kernel::class]->call($command, $parameters);
    }
}
