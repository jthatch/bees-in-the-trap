<?php

declare(strict_types=1);

namespace BeesInTheTrap;

use RuntimeException;

class Hive
{
    private BeeColony $beeColony;

    public function __construct(BeeColony $beeColony)
    {
        $this->beeColony = $beeColony;
    }

    public function getColony(): BeeColony
    {
        return $this->beeColony;
    }

    public function isHiveDestroyed(): bool
    {
        if ($this->getQueenBee()->isDead()) {
            return true;
        }

        foreach ($this->getColony() as $bee) {
            if (!$bee->isDead()) {
                return false;
            }
        }

        return true;
    }

    public function getQueenBee(): Bee
    {
        foreach ($this->beeColony as $bee) {
            if ($bee instanceof QueenBee) {
                return $bee;
            }
        }

        throw new RuntimeException('Couldn\'t find Queen Bee in Hive');
    }
}
