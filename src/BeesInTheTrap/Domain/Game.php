<?php

declare(strict_types=1);

namespace BeesInTheTrap;

use BeesInTheTrap\Exception\GameOverException;
use Exception;

class Game
{
    use GameTurnOutputTrait;

    private Hive $hive;

    private int $hits = 0;

    private bool $gameOver = false;

    public function __construct(Hive $hive)
    {
        $this->hive = $hive;
    }

    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    public function takeTurn(): string
    {
        if ($this->isGameOver()) {
            throw new GameOverException('Game Over!');
        }

        ++$this->hits;
        $bee    = $this->getRandomAliveBee();
        $damage = $bee->takeHit();

        if ($this->hive->isHiveDestroyed()) {
            $this->gameOver = true;
        }

        return sprintf('%s Hit. You %s %d damage to a %s bee%s',
            $bee->isDead() ? 'Fatal' : 'Direct',
            $this->getRandomHitWord(),
            $damage,
            $bee->getType(),
            !$bee->isDead() && $bee->isCloseToDeath() ? ' and it\'s close to death' : ''
        );
    }

    public function getRandomAliveBee(): Bee
    {
        $colonyBeeCount = count($this->hive->getColony());
        do {
            try {
                $bee = $this->hive->getColony()->get(random_int(0, $colonyBeeCount - 1));
            } catch (Exception $e) {
                /* @noinspection RandomApiMigrationInspection */
                $bee = $this->hive->getColony()->get(mt_rand(0, $colonyBeeCount - 1));
            }
        } while ($bee->isDead());

        return $bee;
    }
}
