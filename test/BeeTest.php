<?php

declare(strict_types=1);

namespace Test;

use BeesInTheTrap\Bee;
use BeesInTheTrap\BeeFactory;
use PHPUnit\Framework\TestCase;

class BeeTest extends TestCase
{
    public function testQueen(): void
    {
        $initialHealth = 100;

        $bee = BeeFactory::create(BeeFactory::QUEEN);
        $this->assertEquals($initialHealth, $bee->getHealth());
        $damage = $bee->takeHit();
        $this->assertEquals(8, $damage);
        $this->assertEquals(8, $initialHealth - $bee->getHealth());
        $this->assertEquals('queen', $bee->getType());
        $this->assertFalse($bee->isCloseToDeath());
        $this->assertFalse($bee->isDead());

        while (!$bee->isCloseToDeath()) {
            $bee->takeHit();
        }
        $this->assertTrue($bee->isCloseToDeath());
        $this->assertFalse($bee->isDead());
        $bee->takeHit();
        $this->assertTrue($bee->isDead());
    }

    public function testFactoryHandlesNonLowercaseQueen(): void
    {
        $bee = BeeFactory::create(strtoupper(BeeFactory::QUEEN));
        /* @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(Bee::class, $bee);
    }

    public function testWorker(): void
    {
        $initialHealth = 75;

        $bee = BeeFactory::create(BeeFactory::WORKER);
        $this->assertEquals($initialHealth, $bee->getHealth());
        $damage = $bee->takeHit();
        $this->assertEquals(10, $damage);
        $this->assertEquals(10, $initialHealth - $bee->getHealth());
        $this->assertEquals('worker', $bee->getType());
        $this->assertFalse($bee->isCloseToDeath());
        $this->assertFalse($bee->isDead());

        while (!$bee->isCloseToDeath()) {
            $bee->takeHit();
        }
        $this->assertTrue($bee->isCloseToDeath());
        $this->assertFalse($bee->isDead());
        $bee->takeHit();
        $this->assertTrue($bee->isDead());
    }

    public function testDrone(): void
    {
        $initialHealth = 50;

        $bee = BeeFactory::create(BeeFactory::DRONE);
        $this->assertEquals($initialHealth, $bee->getHealth());
        $damage = $bee->takeHit();
        $this->assertEquals(12, $damage);
        $this->assertEquals(12, $initialHealth - $bee->getHealth());
        $this->assertEquals('drone', $bee->getType());
        $this->assertFalse($bee->isCloseToDeath());
        $this->assertFalse($bee->isDead());

        while (!$bee->isCloseToDeath()) {
            $bee->takeHit();
        }
        $this->assertTrue($bee->isCloseToDeath());
        $this->assertFalse($bee->isDead());
        $bee->takeHit();
        $this->assertTrue($bee->isDead());
    }

    public function testInvalidBeeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        BeeFactory::create('invalid type');
    }
}
