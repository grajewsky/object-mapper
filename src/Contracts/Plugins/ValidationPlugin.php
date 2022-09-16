<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Contracts\Plugins;

use Grajewsky\ObjectMapper\Exceptions\ValidationException;

interface ValidationPlugin extends AnnotationPlugin
{
    /**
     * @param \ReflectionProperty $property
     * @param object $source
     * @return bool
     * @throws ValidationException
     */
    public function check(\ReflectionProperty $property, object $source): bool;
}
