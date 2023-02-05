<?php declare(strict_types = 1);

namespace Middle\Skeleton;

use Middle\Skeleton\Command\ExampleCommand;
use Middle\Skeleton\Command\StartWebserverCommand;
use Symfony\Component\Console\Application;

class ConsoleCommandsProvider
{
    private ServiceContainer $container;

    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    public function registerCommands(Application $application): void
    {
        $application->add(new ExampleCommand());
        $application->add(new StartWebserverCommand());
    }
}
