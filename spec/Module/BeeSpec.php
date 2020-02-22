<?php

declare(strict_types=1);

namespace spec\BeesInTheTrap\Module;

use BeesInTheTrap\Module\Bee;
use PhpSpec\ObjectBehavior;

class BeeSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('queen', 1, 100, 8, 1, true);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Bee::class);
    }

    public function it_implements_splsubject(): void
    {
        $this->shouldImplement(\SplSubject::class);
    }

    public function it_can_take_a_hit(): void
    {
        $this->takeHit();
        $this->getHealth()->shouldReturn(92);
        $this->isDead()->shouldReturn(false);
    }

    public function it_will_know_when_its_close_to_death(): void
    {
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();

        $this->getHealth()->shouldReturn(4);
        $this->isCloseToDeath()->shouldReturn(true);
        $this->isDead()->shouldReturn(false);
        $this->isSupersedure()->shouldReturn(true);
    }

    public function it_will_die_after_being_hit_to_death(): void
    {
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();
        $this->takeHit();

        $this->getHealth()->shouldReturn(0);
        $this->isDead()->shouldReturn(true);
        $this->isSupersedure()->shouldReturn(true);
    }

    public function it_will_die_after_a_supersedure_event(): void
    {
        $this->triggerSupersedure();
        $this->isDead()->shouldReturn(true);
    }
}
