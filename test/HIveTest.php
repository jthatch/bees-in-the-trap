<?php

declare(strict_types=1);

namespace Test;

use BeesInTheTrap\BeeColony;
use BeesInTheTrap\DroneBee;
use BeesInTheTrap\Hive;
use BeesInTheTrap\HiveFactory;
use BeesInTheTrap\QueenBee;
use BeesInTheTrap\WorkerBee;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class HIveTest extends TestCase
{
    public function testHiveComposition(): void
    {
        $hive = HiveFactory::create();
        $this->assertCount(14, $hive->getColony());

        $queenCount  = 0;
        $workerCount = 0;
        $droneCount  = 0;

        foreach ($hive->getColony() as $bee) {
            $beeClass = get_class($bee);
            switch ($beeClass) {
                case QueenBee::class:
                    $queenCount++;
                    break;
                case WorkerBee::class:
                    $workerCount++;
                    break;
                case DroneBee::class:
                    $droneCount++;
                    break;
                default:
                    throw new RuntimeException("Unable to find bee of type: {$beeClass}");
            }
        }

        $this->assertEquals(1, $queenCount);
        $this->assertEquals(5, $workerCount);
        $this->assertEquals(8, $droneCount);
    }

    public function testHiveDestroyedWhenQueenBeeDies(): void
    {
        $queenBee  = new QueenBee();
        $workerBee = new WorkerBee();
        $hive      = new Hive(new BeeColony([$queenBee, $workerBee]));

        $this->assertFalse($hive->isHiveDestroyed());

        for ($i = 0; $i < 14; ++$i) {
            $queenBee->takeHit();
        }

        $this->assertTrue($hive->isHiveDestroyed());
    }

    public function testHiveDestroyedWhenAllBeesAreDead(): void
    {
        $workerBee = new WorkerBee();
        $droneBee  = new DroneBee();
        $queenBee  = new QueenBee();
        $hive      = new Hive(new BeeColony([$workerBee, $droneBee, $queenBee]));

        $this->assertFalse($hive->isHiveDestroyed());

        for ($i = 0; $i < 8; ++$i) {
            $workerBee->takeHit();
        }
        for ($i = 0; $i < 5; ++$i) {
            $droneBee->takeHit();
        }
        for ($i = 0; $i < 14; ++$i) {
            $queenBee->takeHit();
        }

        $this->assertTrue($hive->isHiveDestroyed());
    }

    public function testHiveRequiresQueenBee(): void
    {
        $this->expectException(RuntimeException::class);
        $hive     = new Hive(new BeeColony([new WorkerBee()]));
        $hive->isHiveDestroyed();
    }
}
