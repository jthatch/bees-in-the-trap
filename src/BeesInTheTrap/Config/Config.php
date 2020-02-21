<?php

declare(strict_types=1);

namespace BeesInTheTrap\Config;

/**
 * Class Config
 * Read the environmental variables and return an array of bee's in the trap
 * ENV format: {PREFIX}_{TYPE}_{LIFESPAN|SUPERSEDURE|QUANTITY|DAMAGE}
 * E.G.
 * BEE_QUEEN_LIFESPAN=1
 * BEE_WORKER_.
 *
 * The resulting array will contain an array of variables indexed by bee type
 */
class Config
{
    /** @var string */
    protected const ENV_PREFIX = 'BEE_';

    public static function get(): array
    {
        $config = [];
        $env    = getenv();

        array_walk($env, static function ($value, $key) use (&$config): void {
            if (0 === strpos($key, static::ENV_PREFIX)) {
                [, $type, $attrib] = explode('_', $key, 3);
                $type = strtolower($type);
                $attrib = strtolower($attrib);
                $config[$type] = $config[$type] ?? [];
                $config[$type][$attrib] = (int) $value;
            }
        });

        return $config;
    }
}
