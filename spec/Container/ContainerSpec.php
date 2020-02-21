<?php

declare(strict_types=1);

namespace spec\BeesInTheTrap\Container;

use BeesInTheTrap\Console\Display\WelcomeMessage;
use BeesInTheTrap\Console\PlayCommand;
use BeesInTheTrap\Container\Container;
use BeesInTheTrap\Container\NotFoundException;
use PhpSpec\ObjectBehavior;

class ContainerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Container::class);
    }

    public function it_knows_when_a_service_exists(): void
    {
        $this::build()
            ->has(WelcomeMessage::class)
            ->shouldReturn(true);
    }

    public function it_knows_when_service_does_not_exist(): void
    {
        $this::build()
            ->has('unknown')
            ->shouldReturn(false);
    }

    public function it_can_get_a_service(): void
    {
        $this::build()
            ->get(PlayCommand::class)
            ->shouldReturnAnInstanceOf(PlayCommand::class);
    }

    public function it_fails_when_service_does_not_exist(): void
    {
        $container = $this::build();

        $container->shouldThrow(NotFoundException::class)
            ->duringGet('unknown');
    }
}
