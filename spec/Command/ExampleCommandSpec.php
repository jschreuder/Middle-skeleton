<?php

namespace spec\Middle\Skeleton\Command;

use Middle\Skeleton\Command\ExampleCommand;
use Middle\Skeleton\Service\ExampleService;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleCommandSpec extends ObjectBehavior
{
    private $exampleService;

    public function let(ExampleService $exampleService)
    {
        $this->exampleService = $exampleService;
        $this->beConstructedWith($exampleService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ExampleCommand::class);

        $this->getName()->shouldReturn('middle:example');
        $this->getDescription()->shouldReturn('Example CLI Command');
    }

    public function it_can_execute(InputInterface $input, OutputInterface $output)
    {
        $this->exampleService->getMessage()->willReturn('Hello World');
        $input->getArgument('name')->willReturn('Jelmer');
        $output->writeln('Hello World to you Jelmer')->shouldBeCalled();
        $this->execute($input, $output);
    }
}
