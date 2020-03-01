<?php

declare(strict_types=1);

namespace Test;

use BeesInTheTrap\BeeColony;
use BeesInTheTrap\DroneBee;
use BeesInTheTrap\QueenBee;
use BeesInTheTrap\WorkerBee;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class BeeColonyTest extends TestCase
{
    public function testCanIterateOverBeeColony(): void
    {
        $beeColony = new BeeColony(
            [
                new QueenBee(),
                new WorkerBee(),
                new WorkerBee(),
                new DroneBee(),
                new DroneBee(),
            ]
        );
        $beeTypes  = [];
        foreach ($beeColony as $bee) {
            $beeTypes[] = $bee->getType();
        }

        $this->assertSame(
            [
                'queen',
                'worker',
                'worker',
                'drone',
                'drone',
            ],
            $beeTypes
        );
    }

    public function testOnlyInstancesOfBeeAllowed(): void
    {
        $this->expectException(RuntimeException::class);
        $bees = [new QueenBee(), new WorkerBee(), new DroneBee(), new \stdClass()];
        new BeeColony($bees);
    }

    public function testValid(): void
    {
        $bees      = [new QueenBee()];
        $beeColony = new BeeColony($bees);
        $this->assertTrue($beeColony->valid());
        $beeColony->next();
        $this->assertFalse($beeColony->valid());
    }

    public function testCurrent(): void
    {
        $bees      = [new QueenBee(), new WorkerBee(), new DroneBee()];
        $beeColony = new BeeColony($bees);
        $this->assertTrue($beeColony->valid());
        foreach ($beeColony as $counter => $bee) {
            $this->assertEquals($bees[$counter], $bee);
        }
    }

    public function testCount(): void
    {
        $bees = [];
        foreach (range(1, 10) as $i) {
            $bees[] = new DroneBee();
        }

        $beeColony = new BeeColony($bees);
        $this->assertCount(10, $beeColony);
    }
}
