<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Annotations;

use Grajewsky\ObjectMapper\Contracts\Plugins\ValidationPlugin;
use Grajewsky\ObjectMapper\Exceptions\Plugins\RequiredException;

#[\Attribute]
class Required implements ValidationPlugin
{
    public function __construct(public bool $isRequired = true) {}

    public function check(\ReflectionProperty $property, object $source): bool
    {
        if ($this->isRequired) {
            $value = $property->getValue($source);
            if (empty($value)) {
                throw new RequiredException($property);
            }
        }

        return true;
    }
}
