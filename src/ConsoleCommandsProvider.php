<?php declare(strict_types = 1);

namespace Middle\Skeleton;

use Middle\Skeleton\Command\ExampleCommand;
use Middle\Skeleton\Command\StartWebserverCommand;
use Symfony\Component\Console\Application;

class ConsoleCommandsProvider
{
    public function __construct(
        private ServiceContainer $container
    )
    {
    }

    public function registerCommands(Application $application): void
    {
        $application->add(new ExampleCommand($this->container->getExample()));
        $application->add(new StartWebserverCommand());
    }
}
