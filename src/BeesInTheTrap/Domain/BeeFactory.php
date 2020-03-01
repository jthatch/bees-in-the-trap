<?php

declare(strict_types=1);

namespace BeesInTheTrap;

use InvalidArgumentException;

final class BeeFactory
{
    public const QUEEN  = 'queen';
    public const WORKER = 'worker';
    public const DRONE  = 'drone';

    public static function create(string $type): Bee
    {
        switch (strtolower($type)) {
            case self::QUEEN:
                return new QueenBee();
            case self::WORKER:
                return new WorkerBee();
            case self::DRONE:
                return new DroneBee();
            default:
                throw new InvalidArgumentException("Unknown Bee type: \"{$type}\"");
                break;
        }
    }
}
