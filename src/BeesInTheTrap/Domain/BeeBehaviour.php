<?php

declare(strict_types=1);

namespace BeesInTheTrap;

abstract class BeeBehaviour
{
    use GameTurnOutputTrait;

    protected int $health;

    protected int $damage;

    protected string $type;

    public function __construct()
    {
        $this->health = static::HEALTH;
        $this->damage = static::DAMAGE;
        $this->type   = static::TYPE;
    }

    public function takeHit(): int
    {
        $this->health -= $this->damage;

        return $this->damage;
    }

    public function isCloseToDeath(): bool
    {
        return $this->health - $this->damage < 1;
    }

    public function isDead(): bool
    {
        return $this->health < 1;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getHealth(): int
    {
        return $this->health;
    }
}
