<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper;

use Grajewsky\ObjectMapper\Annotations\Required;
use Grajewsky\ObjectMapper\Exceptions\EmptyRequiredPropertyException;

class ObjectMapper
{
    private array $properties = [];

    public function __construct() { }

    private function cast($value, string $type)
    {
        return match ($type) {
            "double" => doubleval($value),
            "float" => floatval($value),
            "int", "integer" => intval($value),
            "string" => strval($value),
            "array" => (array) $value,
            default => $value
        };
    }

    private function getSourceValue(\ReflectionProperty|string $name): mixed
    {
        $name = (is_string($name)) ? $name : $name->getName();

        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        return null;
    }

    private function isPrimitive(string $type): bool
    {
        return in_array($type, ['string', 'integer', 'float', 'double']);
    }

    /**
     * @template T
     * @param class-string<T> $resultClass
     * @return T|null
     * @throws \ReflectionException
     * @throws EmptyRequiredPropertyException
     */
    private function transform(string $resultClass)
    {
        $refClass = new \ReflectionClass($resultClass);
        $object = $refClass->newInstanceWithoutConstructor();

        foreach ($refClass->getProperties() as $reflectionProperty) {

            if (AnnotationsHandler::isRequired($reflectionProperty)) {
                $value = $this->properties[$reflectionProperty->getName()];
                if (empty($value)) {
                    throw new EmptyRequiredPropertyException();
                }
            }
            if ($reflectionProperty->getType() === null) {
                // Empty type
                $reflectionProperty->setValue($object, $this->properties[$reflectionProperty->getName()]);
            } elseif ($reflectionProperty->getType()?->isBuiltin() && $this->isPrimitive($reflectionProperty->getType()->getName())) {
                // handle property primitive type
                $reflectionProperty->setValue(
                    $object,
                    $this->cast(
                        $this->getSourceValue($reflectionProperty),
                        $reflectionProperty->getType()->getName()
                    )
                );
            } elseif ($reflectionProperty->getType()->getName() === 'array') {
                // handle property array type
                $value = $this->getSourceValue($reflectionProperty);
                $reflectionProperty->setValue($object, (array) $value);
            } elseif ($reflectionProperty->getType()->getName() === 'object') {
                // handle property object type
                $value = $this->getSourceValue($reflectionProperty);
                $reflectionProperty->setValue($object, (object) $value);
            } elseif ($reflectionProperty->getType()->isBuiltin() ===  false) {
                // handle property complex type
                $source = $this->getSourceValue($reflectionProperty);
                if (is_array($source) || is_object($source)) {
                    $ref = ObjectMapper::mapper(
                        $source,
                        $reflectionProperty->getType()->getName()
                    );
                    $reflectionProperty->setValue($object, $ref);
                }
            }
        }

        return $object;
    }

    /**
     * @template T
     * @param array|object $source
     * @param class-string<T> $resultClass
     * @return T|null
     * @throws \ReflectionException
     */
    public static function mapper(array|object $source, string $resultClass)
    {
        $mapper = new ObjectMapper();
        return $mapper->map($source, $resultClass);
    }

    /**
     * @template T
     * @param array|object $source
     * @param class-string<T> $resultClass
     * @return T|null
     * @throws \ReflectionException
     */
    public function map(array|object $source, string $resultClass)
    {
        if (is_array($source)) {
            $this->properties = $source;
        } elseif (is_object($source)) {
            $this->properties = ObjectSourceReader::read($source);
        }

        return $this->transform($resultClass);
    }

}
