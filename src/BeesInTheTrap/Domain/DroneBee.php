<?php

declare(strict_types=1);

namespace BeesInTheTrap;

final class DroneBee extends BeeBehaviour implements Bee
{
    public    const TYPE   = 'drone';
    protected const HEALTH = 50;
    protected const DAMAGE = 12;
}
