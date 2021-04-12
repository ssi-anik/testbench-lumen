<?php

namespace Anik\Testbench\Tests\Extensions;

use Closure;

class DontUseThisTestMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
