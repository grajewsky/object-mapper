<?php

declare(strict_types=1);

namespace Grajewsky\ObjectMapper\Testing;

class SampleClass
{
    public int $id;

    private string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}
