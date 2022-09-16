<?php

declare(strict_types=1);


namespace Grajewsky\ObjectMapper\Exceptions\Plugins;

use Grajewsky\ObjectMapper\Exceptions\ValidationException;

class RequiredException extends ValidationException
{
    private \ReflectionProperty $reflectionProperty;

    public function __construct(string|\ReflectionProperty $data)
    {
        $message = is_string($data)
            ? $data
            : sprintf('Property `%s` is required', $data->getName());

        parent::__construct($message);
    }
}
