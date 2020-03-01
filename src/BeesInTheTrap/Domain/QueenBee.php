<?php

declare(strict_types=1);

namespace BeesInTheTrap;

final class QueenBee extends BeeBehaviour implements Bee
{
    public    const TYPE   = 'queen';
    protected const HEALTH = 100;
    protected const DAMAGE = 8;
}
