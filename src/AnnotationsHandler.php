<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper;

use Grajewsky\ObjectMapper\Annotations\Required;
use Grajewsky\ObjectMapper\Contracts\Plugins\FilterPlugin;
use Grajewsky\ObjectMapper\Contracts\Plugins\MapPlugin;
use Grajewsky\ObjectMapper\Contracts\Plugins\ValidationPlugin;
use Grajewsky\ObjectMapper\Exceptions\FilterException;
use Grajewsky\ObjectMapper\Exceptions\ValidationException;

class AnnotationsHandler
{
    /**
     * @param \ReflectionProperty $reflectionProperty
     * @param mixed $source
     * @param object $targetObject
     * @param array<ValidationPlugin> $validators
     * @param array<MapPlugin> $mapPlugins
     */
    public function __construct(
        private readonly \ReflectionProperty $reflectionProperty,
        private readonly mixed $source,
        private readonly object $targetObject,
        private readonly array $validators,
        private readonly array $mapPlugins
    ) {}

    public static function handler(\ReflectionProperty $reflectionProperty, $source, object $targetObject, array $filters = []): AnnotationsHandler
    {
        $attrs = $reflectionProperty->getAttributes();
        $validators = [];
        $maps = [];

        foreach ($attrs as $reflectionAttribute) {
            $instance = $reflectionAttribute->newInstance();
            if ($instance instanceof ValidationPlugin) {
                $validators[] = $instance;
            } elseif ($instance instanceof MapPlugin) {
                $maps[] = $instance;
            }
        }

        foreach ($filters as $filter) {
            if ($filter instanceof ValidationPlugin) {
                $validators[] = $filter;
            } else if ($filter instanceof MapPlugin) {
                $maps[] = $filter;
            }
        }

        return new AnnotationsHandler($reflectionProperty, $source, $targetObject, $validators, $maps);
    }

    /**
     * @throws ValidationException
     */
    public function isValid(): bool
    {
        foreach ($this->validators as $validator) {
            if ($validator->check($this->reflectionProperty, $this->targetObject) === false) {
                return false;
            }
        }

        return true;
    }

    public function hasBeenMapped(): bool
    {
        if (empty($this->mapPlugins)) {
            return false;
        }

        $result = false;

        foreach ($this->mapPlugins as $mapPlugin) {
            $output = $mapPlugin->map($this->reflectionProperty, $this->source, $this->targetObject);
            $result = ($result === true) ? true : $output;
        }

        return $result;
    }
}
