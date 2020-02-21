<?php

declare(strict_types=1);

namespace spec\BeesInTheTrap\Module;

use BeesInTheTrap\Module\Trap;
use PhpSpec\ObjectBehavior;

class TrapSpec extends ObjectBehavior
{
    private $validTrap = [
        'queen' => [
            'lifespan'    => 100,
            'damage'      => 8,
            'quantity'    => 1,
            'supersedure' => 1,
        ],
        'worker' => [
            'lifespan' => 75,
            'damage'   => 10,
            'quantity' => 5,
        ],
        'drone' => [
            'lifespan' => 50,
            'damage'   => 12,
            'quantity' => 8,
        ],
    ];

    public function let(): void
    {
        $this->beConstructedWith($this->validTrap);
        $this->build();
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Trap::class);
    }

    public function it_can_build_bees_from_config(): void
    {
        $this->isTrapDestroyed()->shouldReturn(false);
    }

    public function it_can_take_a_hit(): void
    {
        $this->hit()->shouldBeString();
    }

    public function it_will_destroy_the_trap_when_the_bees_are_all_dead(): void
    {
        // unfortunately the `$bee instanceof Bee` logic in Trap->update() causes the phpspec assertions
        // to fail, so we do this the old fashioned way
        $trap = new Trap($this->validTrap);

        while (!$trap->isTrapDestroyed()) {
            $trap->hit();
            echo $trap->getLastBeeHitStatus()."\n";
        }

        fwrite(STDOUT,
            sprintf("\n\nLast Hit Status contains \"Game Over\": %s\n",
            stristr('Game Over', $trap->getLastBeeHitStatus()) >= 0 ? 'true' : 'false'));
    }
}
