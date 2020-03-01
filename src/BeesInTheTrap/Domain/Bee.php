<?php

declare(strict_types=1);

namespace BeesInTheTrap;

interface Bee
{
    public function getType(): string;

    public function getHealth(): int;

    public function takeHit(): int;

    public function isCloseToDeath(): bool;

    public function isDead(): bool;
}
