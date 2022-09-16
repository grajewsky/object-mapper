<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Testing;

use Grajewsky\ObjectMapper\Contracts\Plugins\MapPlugin;

#[\Attribute]
class JsonStringify implements MapPlugin
{
    public function map(\ReflectionProperty $property, $source, object $target): bool
    {
        $property->setValue($target, json_encode($source));
        return true;
    }
}
