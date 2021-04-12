<?php

namespace Anik\Testbench\Concerns;

use Anik\Testbench\TestCase;
use PHPUnit\Util\Test;

trait Annotation
{
    protected function parseAnnotation(string $annotation): array
    {
        if (false === $this instanceof TestCase) {
            return [];
        }

        $annotations = Test::parseTestMethodAnnotations(static::class, $this->getName(false));

        return $annotations['method'][$annotation] ?? [];
    }
}
