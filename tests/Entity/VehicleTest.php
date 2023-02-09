<?php

namespace App\Tests\Entity;

use App\Entity\Vehicle;
use PHPUnit\Framework\TestCase;

class VehicleTest extends TestCase
{
    private function getVehicle(): Vehicle
    {
        $driver = new Vehicle();
        $driver->setName("Nome veiculo");
        $driver->setPlate("PPP7G89");
        $driver->setColor("Preto");
        $driver->setCreatedAt(new \DateTimeImmutable());
        $driver->setUpdatedAt(new \DateTimeImmutable());
        return $driver;
    }

    public function testCanGetAndSetData(): void
    {

        self::assertSame('Nome veiculo', $this->getVehicle()->getName());
        self::assertSame('PPP7G89', $this->getVehicle()->getPlate());
        self::assertSame('Preto', $this->getVehicle()->getColor());
    }

    public function testTypes(): void
    {
        self::assertIsString($this->getVehicle()->getName());
        self::assertIsString($this->getVehicle()->getPlate());
        self::assertIsString($this->getVehicle()->getColor());
        self::assertNotNull($this->getVehicle()->getCreatedAt());
        self::assertNotNull($this->getVehicle()->getUpdatedAt());
    }
}
