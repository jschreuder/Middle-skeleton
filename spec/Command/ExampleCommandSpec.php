<?php

namespace spec\Middle\Skeleton\Command;

use Middle\Skeleton\Command\ExampleCommand;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ExampleCommand::class);

        $this->getName()->shouldReturn('middle:example');
        $this->getDescription()->shouldReturn('Example CLI Command');
    }

    public function it_can_execute(InputInterface $input, OutputInterface $output)
    {
        $input->getArgument('name')->willReturn('Jelmer');
        $output->writeln('Hello Jelmer')->shouldBeCalled();
        $this->execute($input, $output);
    }
}
