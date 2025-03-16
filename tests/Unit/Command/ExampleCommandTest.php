<?php

use Middle\Skeleton\Command\ExampleCommand;
use Middle\Skeleton\Service\ExampleService;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

test('example command outputs welcome message', function () {
    $service = mock(ExampleService::class);
    $service->shouldReceive('getMessage')
        ->once()
        ->andReturn('Hello world!');

    $command = new ExampleCommand($service);
    
    $input = new ArrayInput(['name' => 'John']);
    $output = new BufferedOutput();
    
    $command->run($input, $output);
    
    expect($output->fetch())->toContain('Hello world! to you John');
}); 