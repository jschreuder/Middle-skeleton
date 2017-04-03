<?php declare(strict_types = 1);

namespace Middle\Skeleton;

use jschreuder\Middle\ApplicationStack;
use jschreuder\Middle\Controller\CallableController;
use jschreuder\Middle\Controller\ControllerRunner;
use jschreuder\Middle\Router\RouterInterface;
use jschreuder\Middle\Router\SymfonyRouter;
use jschreuder\Middle\ServerMiddleware\ErrorHandlerMiddleware;
use jschreuder\Middle\ServerMiddleware\JsonRequestParserMiddleware;
use jschreuder\Middle\ServerMiddleware\RoutingMiddleware;
use Middle\Skeleton\Service\ExampleService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class GeneralServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container['app'] = function (Container $container) {
            return new ApplicationStack([
                new ControllerRunner(),
                new JsonRequestParserMiddleware(),
                new RoutingMiddleware(
                    $container['app.router'],
                    $container['app.error_handlers.404']
                ),
                new ErrorHandlerMiddleware(
                    $container['logger'],
                    $container['app.error_handlers.500']
                )
            ]);
        };

        $container['app.router'] = function () use ($container) {
            return new SymfonyRouter($container['site.url']);
        };

        $container['app.url_generator'] = function () use ($container) {
            /** @var  RouterInterface $router */
            $router = $container['app.router'];
            return $router->getGenerator();
        };

        $container['app.error_handlers.404'] = CallableController::factoryFromCallable(
            function (ServerRequestInterface $request) use ($container) : ResponseInterface {
                return new JsonResponse(
                    [
                        'message' => 'Not found: ' .
                            $request->getMethod() . ' ' . $request->getUri()->getPath(),
                    ],
                    404
                );
            }
        );

        $container['app.error_handlers.500'] = CallableController::factoryFromCallable(
            function () use ($container) : ResponseInterface {
                return new JsonResponse(['message' => 'System Error'], 500);
            }
        );

        $container['db'] = function (Container $container) {
            return new \PDO(
                $container['db.dsn'] . ';dbname=' . $container['db.dbname'],
                $container['db.user'],
                $container['db.pass'],
                [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
        };

        $container['service.example'] = function () {
            return new ExampleService();
        };
    }
}
