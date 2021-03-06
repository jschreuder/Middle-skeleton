<?php declare(strict_types = 1);

namespace Middle\Skeleton\Controller;

use jschreuder\Middle\Controller\ControllerInterface;
use Middle\Skeleton\Service\ExampleService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ExampleController implements ControllerInterface
{
    /** @var  ExampleService */
    private $exampleService;

    public function __construct(ExampleService $exampleService)
    {
        $this->exampleService = $exampleService;
    }

    public function execute(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['message' => $this->exampleService->getMessage()]);
    }
}
