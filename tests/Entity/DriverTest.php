<?php

namespace App\Tests\Entity;

use App\Entity\Driver;
use App\Entity\Vehicle;
use PHPUnit\Framework\TestCase;

class DriverTest extends TestCase
{
    private function getDriver(): Driver
    {
        $driver = new Driver();
        $driver->setName("Nome Motorista");
        $driver->setDocument("03150790026");
        $driver->setCreatedAt(new \DateTimeImmutable());
        $driver->setUpdatedAt(new \DateTimeImmutable());
        $driver->setVehicle(new Vehicle());
        return $driver;
    }

    public function testCanGetAndSetData(): void
    {

        self::assertSame('Nome Motorista', $this->getDriver()->getName());
        self::assertSame('03150790026', $this->getDriver()->getDocument());
        self::assertInstanceOf(Vehicle::class, $this->getDriver()->getVehicle());
    }

    public function testTypes(): void
    {
        self::assertIsString($this->getDriver()->getName());
        self::assertIsNumeric($this->getDriver()->getDocument());
        self::assertIsObject($this->getDriver()->getVehicle());
        self::assertNotNull($this->getDriver()->getCreatedAt());
        self::assertNotNull($this->getDriver()->getUpdatedAt());
    }
}
