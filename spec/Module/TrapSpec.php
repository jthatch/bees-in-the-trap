<?php

declare(strict_types=1);

namespace spec\BeesInTheTrap\Module;

use BeesInTheTrap\Module\Trap;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\NullOutput;

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

    public function let(NullOutput $output): void
    {
        $this->beConstructedWith($this->validTrap);
        $this->setVerboseOutput($output);
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

    /**
     * unfortunately the `$bee instanceof Bee` check in Trap->update() prevents the mocked objects being triggered
     * in phpspec, so we do this the old fashioned way.
     */
    public function it_will_destroy_the_trap_when_the_bees_are_all_dead(): void
    {
        $hitCount = [];
        $i        = 0;
        $output   = new NullOutput();
        fwrite(STDOUT, 'Simulating 100 games'."\n");
        while ($i++ < 100) {
            $trap = new Trap($this->validTrap);
            $trap
                ->setVerboseOutput($output)
                ->build();

            while (!$trap->isTrapDestroyed()) {
                $trap->hit();
            }
            $hitCount[] = $trap->getHitCount();
        }

        fwrite(STDOUT, 'Hits taken: '.implode(',', $hitCount)."\n");
        $occurrences = array_count_values($hitCount);
        arsort($occurrences);
        fwrite(STDOUT, 'Occurrences: '.json_encode($occurrences)."\n");
    }
}
