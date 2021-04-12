<?php

namespace Anik\Testbench\Tests\Extensions;

use Carbon\Laravel\ServiceProvider;

class DontUseThisTestServiceProvider extends ServiceProvider
{
    const BIND_NAME = 'bind-name';
    const BOUND_VALUE = 'bound-value';

    public function boot()
    {
        $this->app->bind(self::BIND_NAME, function () {
            return self::BOUND_VALUE;
        });
    }
}
