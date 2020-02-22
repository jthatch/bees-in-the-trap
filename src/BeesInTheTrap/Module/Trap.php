<?php

declare(strict_types=1);

namespace BeesInTheTrap\Module;

use Symfony\Component\Console\Output\OutputInterface;

class Trap implements \SplObserver
{
    /** @var array */
    private $config;

    /** @var OutputInterface */
    private $verbose;

    /** @var array */
    private $bees;

    /** @var array */
    private $livingBeeIds = [];

    /** @var int */
    private $lastBeeHitId;

    /** @var int */
    private $hitCount;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function build(): self
    {
        $beeId = 0;
        foreach ($this->config as $type => $attrib) {
            for ($i = 0; $i < $attrib['quantity']; ++$i) {
                $this->bees[$beeId] = new Bee(
                    $type,
                    $beeId,
                    $attrib['lifespan'],
                    $attrib['damage'],
                    isset($attrib['supersedure']) && 1 === $attrib['supersedure']
                );
                $this->bees[$beeId]->attach($this);
                $this->livingBeeIds[] = $beeId;
                ++$beeId;
            }
        }
        array_walk($this->bees, function (Bee $bee, $id): void {
            $this->verbose->writeln(json_encode($bee->toArray()));
        });
        $this->verbose->writeln(sprintf('Living Bees: %s', json_encode($this->livingBeeIds)));

        return $this;
    }

    public function setVerboseOutput(OutputInterface $output): self
    {
        $this->verbose = $output;

        return $this;
    }

    public function hit(): string
    {
        ++$this->hitCount;
        $id = $this->livingBeeIds[array_rand($this->livingBeeIds, 1)];
        /** @var Bee $bee */
        $bee = $this->bees[$id];

        $bee->takeHit();

        $this->verbose->writeLn(sprintf(
            'Hit: random id: <info>%d</info> Bee stats: <info>%s</info> Alive: <info>%d</info>',
            $id, json_encode($bee->toArray()), count($this->livingBeeIds)));

        return $this->getLastBeeHitStatus();
    }

    /**
     * @param \SplSubject|Bee $bee
     */
    public function update(\SplSubject $bee): void
    {
        if ($bee instanceof Bee) {
            if ($bee->isDead()) {
                if ($bee->isSupersedure() && !$this->isTrapDestroyed()) {
                    $this->triggerSupersedure();
                } else {
                    $this->triggerDeadBee($bee);
                }
            }
            $this->lastBeeHitId = $bee->getId();
        }
    }

    public function getLastBeeHitStatus(): string
    {
        $words      = ['dished out', 'bestowed', 'gracefully delivered', 'accorded', 'lavished', 'dispensed', 'heaped on'];
        $chosenWord = $words[array_rand($words, 1)];
        /** @var Bee $bee */
        $bee = $this->bees[$this->lastBeeHitId];

        if ($this->isTrapDestroyed()) {
            return sprintf('<fg=green;options=bold>Congratulations, Game Over! After %d hits you destroyed the trap!</>',
                $this->hitCount
            );
        }

        if ($bee->isDead()) {
            if ($bee->isSupersedure()) {
                return sprintf('<fg=green;options=bold>Congratulations, Game Over! After %d hits you killed the %s bee and destroyed the trap!</>',
                    $this->hitCount,
                    $bee->getType()
                );
            }

            return sprintf('<fg=red;options=bold>Fatal Hit. You %s <info>%d</info> damage and killed a <info>%s</info> bee</>',
                $chosenWord,
                $bee->getDamage(),
                $bee->getType()
            );
        }

        return sprintf('%sDirect Hit. You %s <info>%d</info> damage to a <info>%s</info> bee%s',
            $bee->isCloseToDeath() ? '<fg=yellow;options=bold>' : '',
            $chosenWord,
            $bee->getDamage(),
            $bee->getType(),
            $bee->isCloseToDeath() ? ' and it\'s close to death!</>' : ''
        );
    }

    public function getHitCount(): int
    {
        return $this->hitCount;
    }

    public function isTrapDestroyed(): bool
    {
        return empty($this->livingBeeIds);
    }

    private function triggerSupersedure(): void
    {
        array_walk($this->bees, function (Bee $bee): void {
            $bee->triggerSupersedure();
            $this->triggerDeadBee($bee);
        });
    }

    private function triggerDeadBee(Bee $bee): void
    {
        try {
            if (!$this->isTrapDestroyed()) {
                if (1 === count($this->livingBeeIds)) {
                    array_pop($this->livingBeeIds);
                } else {
                    $id = array_search($bee->getId(), $this->livingBeeIds, true);
                    if (false !== $id) {
                        array_splice($this->livingBeeIds, array_search($bee->getId(), $this->livingBeeIds, true), 1);
                        $this->livingBeeIds = array_values($this->livingBeeIds);
                    }
                }
            }
        } catch (\Error $e) {
            print_r($e->getMessage());
        }
    }
}
