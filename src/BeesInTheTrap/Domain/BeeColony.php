<?php

declare(strict_types=1);

namespace BeesInTheTrap;

use Countable;
use Exception;
use Iterator;
use RuntimeException;
use Webmozart\Assert\Assert;

class BeeColony implements Countable, Iterator
{
    private array $bees;

    private int $index = 0;

    public function __construct(array $bees)
    {
        try {
            Assert::allIsInstanceOf($bees, Bee::class);
        } catch (Exception $e) {
            throw new RuntimeException(sprintf('Invalid items in colony. All items must be of type: %s', Bee::class));
        }

        $this->bees = $bees;
    }

    public function get(int $index): Bee
    {
        return $this->bees[$index];
    }

    public function count(): int
    {
        return count($this->bees);
    }

    public function current(): Bee
    {
        return $this->bees[$this->index];
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return isset($this->bees[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }
}
