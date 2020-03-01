<?php

declare(strict_types=1);

namespace Test;

use BeesInTheTrap\Exception\GameOverException;
use BeesInTheTrap\GameFactory;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testFullGame(): void
    {
        $game = GameFactory::create();
        $i    = 0;
        do {
            $game->takeTurn();
            ++$i;
        } while (!$game->isGameOver());

        $this->assertGreaterThanOrEqual(13, $i);
        $this->assertLessThanOrEqual(93, $i);

        $this->expectException(GameOverException::class);
        $game->takeTurn();
    }
}
