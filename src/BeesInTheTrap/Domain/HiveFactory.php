<?php

declare(strict_types=1);

namespace BeesInTheTrap;

final class HiveFactory
{
    private const HIVE = [
        BeeFactory::QUEEN  => 1,
        BeeFactory::WORKER => 5,
        BeeFactory::DRONE  => 8,
    ];

    public static function create(): Hive
    {
        $bees = [];

        foreach (self::HIVE as $type => $count) {
            for ($i = 0; $i < $count; ++$i) {
                $bees[] = BeeFactory::create($type);
            }
        }

        return new Hive(new BeeColony($bees));
    }
}
