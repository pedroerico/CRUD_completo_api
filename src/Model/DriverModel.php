<?php

namespace App\Model;

use App\Entity\Driver;
use App\Validator\Constraints as CrudAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Model to driver
 */
class DriverModel
{
    /**
     * @var string
     */
    #[Assert\NotBlank]
    public string $name;

    /**
     * @var string
     */
    #[Assert\NotBlank]
    #[CrudAssert\CpfCnpj]
    public string $document;

    /**
     * @var string
     */
    #[Assert\NotBlank]
    public string $vehicleName;

    /**
     * @var string|null
     */
    public ?string $vehicleColor;

    /**
     * @var string
     */
    #[Assert\NotBlank]
    public string $vehiclePlate;

    public function __construct(?Driver $driver = null)
    {
        if ($driver) {
            $this->name = $driver->getName();
            $this->document = $driver->getDocument();
            $this->vehicleName = $driver->getVehicle()->getName();
            $this->vehiclePlate = $driver->getVehicle()->getPlate();
            $this->vehicleColor = $driver->getVehicle()->getColor();
        }
    }
}
