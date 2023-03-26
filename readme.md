testbench-lumen
[![codecov](https://codecov.io/gh/ssi-anik/testbench-lumen/branch/main/graph/badge.svg?token=14AJIHUXGK)](https://codecov.io/gh/ssi-anik/testbench-lumen)
[![Total Downloads](https://poser.pugx.org/anik/testbench-lumen/downloads)](//packagist.org/packages/anik/testbench-lumen)
[![Latest Stable Version](https://poser.pugx.org/anik/testbench-lumen/v)](//packagist.org/packages/anik/testbench-lumen)
==============

`anik/testbench-lumen` is a package, highly inspired by
the [orchestral/testbench](https://github.com/orchestral/testbench). `orchestral/testbench` that is a tool for testing
Laravel packages. Whereas the `anik/testbench-lumen` can only be used with [Lumen](https://github.com/laravel/lumen),
starting from Lumen `6.x` and afterwards.

## Installation

```shell
composer require --dev anik/testbench-lumen
```

## Documentation

- The package uses the `phpunit.xml`. Set up your environment variables in the `phpunit.xml` file.

```xml

<phpunit>
    // ...
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="CACHE_DRIVER" value="array"/>
    </php>
</phpunit>
```

**NOTE**: The package **doesn't** use the `.env` file. You'll have to primarily set all your variables in
your `phpunit.xml` file.

- Your testcases should extend the `\Anik\Testbench\TestCase` class.
- Your testcases will have access to the [Lumen testing APIs](https://lumen.laravel.com/docs/master/testing).

### Bootstrapping

The package internally boots the Lumen application for the test cases. While bootstrapping, you can add some
functionalities.

- `afterApplicationCreated($callback)` registers the callbacks that will be called after the application is created. If
  you register the callback after the application is created, it'll be fired immediately. The callback will access to
  the `Laravel\Lumen\Application` instance.
- `afterApplicationRefreshed($callback)` registers the callbacks that will be called after the application is refreshed.
  If you register the callback after the application is refreshed, it'll be fired immediately. The callback will have
  access to the `Laravel\Lumen\Application` instance.
- `beforeApplicationDestroyed($callback)` registers the callback that will be called before the application is getting
  destroyed. Will have access to the `Laravel\Lumen\Application` instance.
- `afterApplicationDestroyed($callback)` registers the callback that will be called after the application has been
  destroyed. Will have access to the `Laravel\Lumen\Application` instance.

---

The application does not by default loads the `facade` and `eloquent`. If you need to enable

- Facade, the return `true` from the `withFacade` method. Default: `false`.
- Eloquent, the return `true` from the `withEloquent` method. Default: `false`.

---

To load your required service providers, you can return an **array** of providers from the `serviceProviders()` method.
Default is `[]`.

```php
<?php

protected function serviceProviders(): array
{
    return [
        // AppServiceProvider::class,
        // FormRequestServiceProvider::class,
        // AmqpServiceProvider::class,
    ];
}
```

---

To add your middlewares, you can add both the global and route middlewares.

- To add global middlewares, you have to return an array of middlewares from the `globalMiddlewares` method. The method
  has access to the `Laravel\Lumen\Application` instance.

```php
<?php

protected function globalMiddlewares(Application $app): array
{
    return [
        // CorsMiddleware::class,
        // NewrelicMiddleware::class,
    ];
}
```

- To add route middlewares, you have to return an associative array of middlewares from the `routeMiddlewares`. The
  method has access to the `Laravel\Lumen\Application` instance.

```php
<?php

protected function routeMiddlewares(Application $app): array
{
    return [
        // 'auth' => Authenticate::class,
        // 'admin' => AdminMiddleware::class,
    ];
}
```

---

By default, the application has the access to the`/` endpoint returning the `app()->version()` as the response. To
define your routes for the test purpose, you can use the `routes` method. The method has access to
the `Laravel\Lumen\Routing\Router` instance. Defining routes in this method is as same as writing methods in
the `routes/web.php` or `routes/api.php`

```php
<?php

protected function routes(Router $router): void
{
    $router->get('test-route', function () {
        return response()->json([
            'error' => false,
            'message' => 'Test route is executed'
        ], 202);
    });
}
```

---

If you don't want to report an **Exception**, you can use the `dontReportExceptions` method. The defined exceptions will
not be reported. Default is `[]`.

```php
<?php

protected function dontReportExceptions(): array
{
    return [
        // AuthenticationException::class,
    ];
}
```

---

If it's required to work with the `$app` instance before the service providers are being registered,
then `beforeServiceProviders` is the method to consider. It'll be called for each test methods.

```php
protected function beforeServiceProviders(Application $app)
{
    $app['config']->set(['my-package.enabled' => false]);
}
```

### Annotations

There are three types of annotations considered during the test run. All the annotated tasks are executed synchronously.
All the tasks will receive the `\Laravel\Lumen\Application\Application` instance in their parameter.

- `@pre-service-providers` - Annotated tasks will run before the service providers are being registered. Maybe
  useful for modifying values in config before the service provider gets registered.
- `@post-service-providers` - Annotated tasks will run after the service providers are being registered.
- `@setup-before` - Annotated tasks will run after the application has boot properly and before running each testcases.
  If your tests need to perform some sort of actions before running it, **i.e.** changing environment values, binding
  something to the container, etc. then you can perform those actions with annotations. Only the method level
  annotations are executed.

See [Annotation Test class](https://github.com/ssi-anik/testbench-lumen/blob/main/tests/Integration/AnnotationTest.php)
to get the hang of it.

```php
<?php

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
 * @setup-before secondCalled
 */
public function testMultipleAnnotations()
{
    $this->assertEquals('modified', $this->app->make('value-should-be-found'));
}

public function defineEnvironmentVariables(Application $app)
{
    $app['config']->set(['testbench-lumen.enabled' => true]);
}

/**
 * @pre-service-providers defineEnvironmentVariables 
 */
public function testDefineEnvAnnotation()
{
    $this->assertEquals(true, $this->app['config']->get('testbench-lumen.enabled'));
}
```

---

### Examples

All the scenarios are covered with tests. You can use them as examples.
