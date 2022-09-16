<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Exceptions;

class ValidationException extends \Exception
{
    public function __construct(string $validatorClass, \ReflectionProperty $data, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(
            sprintf("Property `%s::%s` failed on %s validator", $data->class, $data->getName(), $validatorClass),
            $code,
            $previous
        );
    }
}
