<?php

declare(strict_types=1);

namespace spec\BeesInTheTrap\Config;

use BeesInTheTrap\Config\Config;
use PhpSpec\ObjectBehavior;

class ConfigSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Config::class);
    }

    public function it_can_return_an_empty_array_on_no_envs(): void
    {
        $this::get()->shouldReturn([]);
    }

    public function it_can_read_correctly_prefixed_env(): void
    {
        // set some environmental variables
        putenv('BEE_QUEEN_LIFESPAN=100');
        putenv('BEE_QUEEN_QUANTITY=1');
        putenv('BEE_QUEEN_DAMAGE=8');
        putenv('BEE_QUEEN_SUPERSEDURE=1');

        $this::get()->shouldReturn([
            'queen' => [
                'lifespan'    => 100,
                'quantity'    => 1,
                'damage'      => 8,
                'supersedure' => 1, // if set will wipe out all other bees
            ], ]
        );

        // reset
        putenv('BEE_QUEEN_LIFESPAN');
        putenv('BEE_QUEEN_QUANTITY');
        putenv('BEE_QUEEN_DAMAGE');
        putenv('BEE_QUEEN_SUPERSEDURE');
    }

    public function it_can_handle_multiple_types_correctly_prefixed_env(): void
    {
        putenv('BEE_QUEEN_LIFESPAN=100');
        putenv('BEE_WORKER_LIFESPAN=75');
        putenv('BEE_DRONE_LIFESPAN=50');

        $this::get()->shouldReturn([
                'queen' => [
                    'lifespan' => 100,
                ],
                'worker' => [
                    'lifespan' => 75,
                ],
                'drone' => [
                    'lifespan' => 50,
                ],
            ]
        );

        // wipe em after the test
        putenv('BEE_QUEEN_LIFESPAN');
        putenv('BEE_WORKER_LIFESPAN');
        putenv('BEE_DRONE_LIFESPAN');
    }
}
