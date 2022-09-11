<?php

test('map array to class', function() {

    $name = 'Test';

    $arr = [
        "id" => "A",
        'name' => $name
    ];

    $result = \Grajewsky\ObjectMapper\ObjectMapper::mapper($arr, \Grajewsky\ObjectMapper\Testing\SampleClass::class);

    $this->assertSame(0, $result->id);
    $this->assertSame($name, $result->getName());
});
