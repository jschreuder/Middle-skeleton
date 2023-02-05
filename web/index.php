<?php declare(strict_types = 1);

/** @var  \Middle\Skeleton\ServiceContainer $container */
$container = require __DIR__ . '/../config/app_init.php';

/** @var  jschreuder\Middle\ApplicationStackInterface $app */
$app = $container->getApp();

// Register routing
(new \jschreuder\Middle\Router\RoutingProviderCollection(
    new \Middle\Skeleton\GeneralRoutingProvider($container)
))->registerRoutes($container->GetAppRouter());

// Create request from globals
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();

// Execute the application
$response = $app->process($request);

// Output the response
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
