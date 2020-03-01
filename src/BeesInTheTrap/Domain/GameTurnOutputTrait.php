<?php

declare(strict_types=1);

namespace BeesInTheTrap;

use Exception;

trait GameTurnOutputTrait
{
    private array $words = [
        'dished out',
        'bestowed',
        'gracefully delivered',
        'accorded',
        'lavished',
        'dispensed',
        'heaped on',
    ];

    protected function getRandomHitWord(): string
    {
        if (!empty($this->words)) {
            try {
                return $this->words[random_int(0, count($this->words) - 1)];
            } catch (Exception $e) { // Resort to mt_rand if an appropriate source of randomness cannot be found
                /* @noinspection RandomApiMigrationInspection */
                return $this->words[mt_rand(0, count($this->words) - 1)];
            }
        }

        return '';
    }
}
