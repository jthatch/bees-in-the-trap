<?php

declare(strict_types=1);

namespace spec\BeesInTheTrap\Module;

use BeesInTheTrap\Config\Config;
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

    private $emptyTrap = [];

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
        $simulations            = is_numeric(getenv('SIMULATIONS')) ? (int) getenv('SIMULATIONS') : 100;
        $simulationsOutputEvery = (int) ceil($simulations / 100) * 100 / 100;
        $beginMsg               = sprintf('Simulating %d games (. = %d game%s)',
            $simulations,
            $simulationsOutputEvery,
            1 === $simulationsOutputEvery ? '' : 's');
        $hitCount               = [];
        $output                 = new NullOutput();
        $trapConfig             = $this->validTrap;

        // attempt to read env from .env
        // this allows us to play with the number of bees, their lifespan etc and see what interesting results we find
        $dotEnvFile = __DIR__.'/../../.env';
        if (file_exists($dotEnvFile)) {
            array_map(static function ($line): void {
                putenv($line);
            }, file($dotEnvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

            $trapConfig = Config::get();
        }

        fwrite(STDOUT, PHP_EOL.$beginMsg);

        $simulationCount = $simulations;
        while ($simulationCount-- > 0) {
            $trap = new Trap($trapConfig);
            $trap
                ->setVerboseOutput($output)
                ->build();

            while (!$trap->isTrapDestroyed()) {
                $trap->hit();
            }
            $hitCount[] = $trap->getHitCount();
            if (0 === $simulationCount % $simulationsOutputEvery) {
                fwrite(STDOUT, '.');
            }
        }

        fwrite(STDOUT, "\r".str_repeat(' ', $simulations + strlen($beginMsg))."\r${beginMsg} ✔\n");

        fwrite(STDOUT, 'Hits taken: '.implode(',', $hitCount)."\n");
        $occurrences = array_count_values($hitCount);
        arsort($occurrences);
        fwrite(STDOUT, 'Occurrences: '.json_encode($occurrences)."\n");
        fwrite(STDOUT, sprintf("Shortest Game: %d (%d occurrences), Longest Game: %d (%d occurrences)\n",
            min(array_keys($occurrences)),
            $occurrences[min(array_keys($occurrences))],
            max(array_keys($occurrences)),
            $occurrences[max(array_keys($occurrences))]
        ));
    }
}
