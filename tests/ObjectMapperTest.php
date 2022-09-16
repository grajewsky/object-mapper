<?php

test('map array to class', function() {

    $name = 'Test';

    $arr = [
        "id" => "A",
        'names' => ['test'],
        "test" => ['id' => 1],
        "origin" => [
            "id" => 1
        ]
    ];

    $result = \Grajewsky\ObjectMapper\ObjectMapper::mapper(
        $arr,
        \Grajewsky\ObjectMapper\Testing\SampleClass::class
    );
    dd($result);
    $this->assertSame(0, $result->id);
    $this->assertSame($name, $result->getName());
});
