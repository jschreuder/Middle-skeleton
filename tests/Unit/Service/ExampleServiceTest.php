<?php

use Middle\Skeleton\Service\ExampleService;

test('example service returns hello world message', function () {
    $service = new ExampleService();
    
    $result = $service->getMessage();
    
    expect($result)->toBe('Hello world!');
}); 