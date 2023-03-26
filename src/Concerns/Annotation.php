<?php

namespace Anik\Testbench\Concerns;

use PHPUnit\Metadata\Annotation\Parser\Registry as PHPUnit10Registry;
use PHPUnit\Runner\Version;
use PHPUnit\Util\Annotation\Registry as PHPUnit9Registry;
use Throwable;

trait Annotation
{
    protected function parseMethodAnnotations(string $name): array
    {
        if (!class_exists(Version::class)) {
            throw new RuntimeException('Invalid PHPUnit version.');
        }

        [$registry, $method] = version_compare(Version::id(), '10', '>=')
            ? [PHPUnit10Registry::getInstance(), $this->name()]
            : [PHPUnit9Registry::getInstance(), $this->getName(false)];

        try {
            $annotations = $registry->forMethod(static::class, $method)->symbolAnnotations();
        } catch (Throwable $t) {
            return [];
        }

        return $annotations[$name] ?? [];
    }

    protected function runThroughAnnotations(string $name, ...$args)
    {
        $annotations = $this->parseMethodAnnotations($name);
        foreach ($annotations as $annotation) {
            call_user_func_array([$this, $annotation], $args);
        }
    }
}
