<?php declare(strict_types = 1);

/** @var  \Pimple\Container $container */
$container = require __DIR__ . '/../etc/app_init.php';

/** @var  jschreuder\Middle\ApplicationStackInterface $app */
$app = $container['app'];

// Register routing
(new \jschreuder\Middle\Router\RoutingProviderCollection(
    new \Middle\Skeleton\GeneralRoutingProvider($container)
))->registerRoutes($container['app.router']);

// Create request from globals
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

// Execute the application
$response = $app->process($request);

// Output the response
(new Zend\Diactoros\Response\SapiEmitter())->emit($response);