<?php

namespace spec\Middle\Skeleton\Service;

use Middle\Skeleton\Service\ExampleService;
use PhpSpec\ObjectBehavior;

class ExampleServiceSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ExampleService::class);
    }

    public function it_can_give_a_message()
    {
        $this->getMessage()->shouldBeString();
    }
}
