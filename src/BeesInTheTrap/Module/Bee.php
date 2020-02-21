<?php

declare(strict_types=1);

namespace BeesInTheTrap\Module;

class Bee implements \SplSubject
{
    /** @var string */
    private $type;

    /** @var int */
    private $id;

    /** @var int */
    private $health;

    /** @var int */
    private $damage;

    /** @var bool */
    private $supersedure;

    /** @var \SplObjectStorage */
    private $observers;

    /**
     * Bee constructor.
     */
    public function __construct(string $type, int $id, int $health, int $damage, bool $supersedure = false)
    {
        $this->type        = $type;
        $this->id          = $id;
        $this->health      = $health;
        $this->damage      = $damage;
        $this->supersedure = $supersedure;

        $this->observers = new \SplObjectStorage();
    }

    public function takeHit(): void
    {
        $this->health = max($this->health - $this->damage, 0);
        $this->notify();
    }

    public function isDead(): bool
    {
        return $this->health <= 0;
    }

    public function isSupersedure(): bool
    {
        return $this->supersedure;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function triggerSupersedure(): void
    {
        $this->health = 0;
    }

    public function attach(\SplObserver $observer): void
    {
        $this->observers->attach($observer);
        $this->notify();
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        /** @var \SplObserver $observer */
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
