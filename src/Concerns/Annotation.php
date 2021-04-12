<?php

namespace Anik\Testbench\Concerns;

use Anik\Testbench\TestCase;
use PHPUnit\Util\Test;

trait Annotation
{
    protected function parseAnnotation(string $annotation, string $class, string $ofMethod): array
    {
        if (!$this instanceof TestCase) {
            return [];
        }

        $annotations = Test::parseTestMethodAnnotations($class, $ofMethod);

        return $annotations['method'][$annotation] ?? [];
    }
}
