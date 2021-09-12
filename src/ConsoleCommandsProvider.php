<?php declare(strict_types = 1);

namespace Middle\Skeleton;

use jschreuder\Middle\Router\RouterInterface;
use Middle\Skeleton\Command\ExampleCommand;
use Pimple\Container;
use Symfony\Component\Console\Application;

class ConsoleCommandsProvider
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function registerCommands(Application $application): void
    {
        $application->add(new ExampleCommand());
    }
}
