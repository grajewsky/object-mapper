<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper;

use Grajewsky\ObjectMapper\Annotations\Required;

class AnnotationsHandler
{
    public function __construct(private array $reflectionAttributes) { }

    public static function handle(\ReflectionProperty $reflectionProperty)
    {
        // read all first
    }

    public static function isRequired(\ReflectionProperty $reflectionProperty): bool
    {
        $attrs = $reflectionProperty->getAttributes(Required::class);
        if (!empty($attrs)) {
            $required = $attrs[0];
            $instance = $required->newInstance();

            return $instance->value;
        }


        return false;

    }
}
