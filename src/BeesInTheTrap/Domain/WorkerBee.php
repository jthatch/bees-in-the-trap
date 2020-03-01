<?php

declare(strict_types=1);

namespace BeesInTheTrap;

final class WorkerBee extends BeeBehaviour implements Bee
{
    public    const TYPE   = 'worker';
    protected const HEALTH = 75;
    protected const DAMAGE = 10;
}
