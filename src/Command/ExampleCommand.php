<?php declare(strict_types = 1);

namespace Middle\Skeleton\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('middle:example')
            ->setDescription('Example CLI Command')
            ->addArgument('name', InputArgument::OPTIONAL, 'Your name', 'unknown');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello ' . $input->getArgument('name'));
        return 0;
    }
}
