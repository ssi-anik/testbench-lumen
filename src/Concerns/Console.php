<?php

declare(strict_types=1);

namespace Anik\Testbench\Concerns;

use Illuminate\Contracts\Console\Kernel;

trait Console
{
    /**
     * Call artisan command and return code.
     *
     * @param  string|null  $command
     * @param  array  $parameters
     *
     * @return int|Kernel
     */
    public function artisan(?string $command = null, $parameters = [])
    {
        if (is_null($command)) {
            return $this->console();
        }

        return $this->app[Kernel::class]->call($command, $parameters);
    }

    /**
     * Get the latest artisan console output
     *
     * @return string
     */
    public function artisanOutput(): string
    {
        return $this->app[Kernel::class]->output();
    }

    /**
     * Get the Artisan Kernel instance
     *
     * @return Kernel
     */
    public function console(): Kernel
    {
        return $this->app[Kernel::class];
    }
}
