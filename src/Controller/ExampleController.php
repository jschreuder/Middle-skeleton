<?php declare(strict_types = 1);

namespace Middle\Skeleton\Controller;

use jschreuder\Middle\Controller\ControllerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Middle\Skeleton\Service\ExampleService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExampleController implements ControllerInterface
{
    public function __construct(
        private ExampleService $exampleService
    )
    {
    }

    public function execute(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['message' => $this->exampleService->getMessage()]);
    }
}
