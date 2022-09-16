<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Testing;

use Grajewsky\ObjectMapper\Annotations\Required;

class SampleClass
{
    #[Required(false)]
    public $id;

    public array $names;

    public object $test;

    public SampleClass $origin;

}
