<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Contracts\Plugins;

interface MapPlugin extends AnnotationPlugin
{
    /**
     * @param \ReflectionProperty $property
     * @param $source
     * @param object $target
     * @return bool
     *
     * Return true on property mapped succeed or false when map is not required
     */
    public function map(\ReflectionProperty $property, $source, object $target): bool;
}
