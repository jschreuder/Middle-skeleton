<?php

namespace spec\Middle\Skeleton\Controller;

use Middle\Skeleton\Controller\ExampleController;
use Middle\Skeleton\Service\ExampleService;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExampleControllerSpec extends ObjectBehavior
{
    /** @var  ExampleService */
    private $exampleService;

    public function let(ExampleService $exampleService)
    {
        $this->exampleService = $exampleService;
        $this->beConstructedWith($exampleService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ExampleController::class);
    }

    public function it_can_execute(ServerRequestInterface $request)
    {
        $this->exampleService->getMessage()->willReturn('test');
        $this->execute($request)->shouldBeAnInstanceOf(ResponseInterface::class);
    }
}
