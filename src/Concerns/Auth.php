<?php

declare(strict_types=1);

namespace Anik\Testbench\Concerns;

use Illuminate\Contracts\Auth\Authenticatable;

trait Auth
{
    /**
     * Set the currently logged in user for the application.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string|null $driver
     *
     * @return $this
     */
    public function actingAs(Authenticatable $user, $driver = null)
    {
        $this->be($user, $driver);

        return $this;
    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string|null $driver
     *
     * @return void
     */
    public function be(Authenticatable $user, $driver = null)
    {
        $this->app['auth']->guard($driver)->setUser($user);
    }
}
