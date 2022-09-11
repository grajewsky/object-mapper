<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper;

class ObjectSourceReader
{
    /**
     * @param object $source
     * @return array
     */
    public static function read(object $source): array
    {
        $sourceRef = new \ReflectionObject($source);
        $properties = [];

        foreach ($sourceRef->getProperties() as $property) {
            $properties[$sourceRef->getName()] = $property->getValue($source);
        }

        return $properties;
    }
}
