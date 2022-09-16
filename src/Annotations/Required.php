<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Annotations;

#[\Attribute]
class Required
{
    public function __construct(public bool $value = true) {}
}
