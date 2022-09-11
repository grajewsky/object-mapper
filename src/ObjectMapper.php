<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper;

class ObjectMapper
{
    private array $properties = [];

    public function __construct() { }

    private function cast($value, string $type): mixed
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

    /**
     * @template T
     * @param class-string<T> $resultClass
     * @return T|null
     * @throws \ReflectionException
     */
    private function transform(string $resultClass)
    {
        $refClass = new \ReflectionClass($resultClass);
        $object = $refClass->newInstanceWithoutConstructor();

        foreach ($refClass->getProperties() as $reflectionProperty) {
            if (array_key_exists($reflectionProperty->getName(), $this->properties)) {
                $reflectionProperty->setValue($object, $this->cast(
                    $this->properties[$reflectionProperty->getName()],
                    $reflectionProperty->getType()->getName()
                ));
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
