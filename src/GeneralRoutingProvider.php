<?php declare(strict_types = 1);

namespace Middle\Skeleton;

use jschreuder\Middle\Router\RouterInterface;
use jschreuder\Middle\Router\RoutingProviderInterface;
use Middle\Skeleton\Controller\ExampleController;
use Pimple\Container;

class GeneralRoutingProvider implements RoutingProviderInterface
{
    /** @var  Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function registerRoutes(RouterInterface $router)
    {
        $router->get('home', '/', function () {
            return new ExampleController($this->container['service.example']);
        });
    }
}
