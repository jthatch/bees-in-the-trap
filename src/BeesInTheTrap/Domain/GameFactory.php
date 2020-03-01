<?php

declare(strict_types=1);

namespace BeesInTheTrap;

final class GameFactory
{
    public static function create(): Game
    {
        $hive = HiveFactory::create();

        return new Game($hive);
    }
}
