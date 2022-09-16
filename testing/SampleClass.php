<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Testing;

use Grajewsky\ObjectMapper\Annotations\Required;

class SampleClass
{
    #[Required]
    public $id;

//    #[JsonStringify]
    public string $names;

    public object $test;

}
