<?php

namespace App\service;

use App\Entity\Driver;
use App\Entity\Vehicle;
use App\Exception\CustomUnprocessableEntityException;
use App\Model\DriverModel;
use App\Repository\DriverRepository;
use App\Repository\VehicleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DriverService
{
    /**
     * @param ManagerRegistry $doctrine
     * @param TranslatorInterface $translator
     */
    public function __construct(
        protected ManagerRegistry $doctrine,
        protected TranslatorInterface $translator,
    )
    {
    }

    public function create(DriverModel $driverModel): Driver
    {
        $this->checkPlate($driverModel);
        $this->checkDocument($driverModel);

        $entityManager = $this->doctrine->getManager();
        $vehicle = $this->getVehicleRepository()->findOneBy(['plate' => $driverModel->vehiclePlate]) ?? new Vehicle();
        $vehicle->setName($driverModel->vehicleName);
        $vehicle->setPlate($driverModel->vehiclePlate);
        $vehicle->setColor($driverModel->vehicleColor);;
        $entityManager->persist($vehicle);

        $driver = new Driver();
        $driver->setName($driverModel->name);
        $driver->setDocument($driverModel->document);
        $driver->setVehicle($vehicle);
        $entityManager->persist($driver);
        $entityManager->flush();

        return $driver;
    }

    public function update(Driver $driver, DriverModel $driverModel): Driver
    {
        $this->checkPlate($driverModel, $driver);
        $this->checkDocument($driverModel, $driver);

        $entityManager = $this->doctrine->getManager();
        $vehicle = $this->getVehicleRepository()->findOneBy(['plate' => $driverModel->vehiclePlate]) ?? $driver->getVehicle();
        $vehicle->setName($driverModel->vehicleName);
        $vehicle->setPlate($driverModel->vehiclePlate);
        $vehicle->setColor($driverModel->vehicleColor);
        $entityManager->persist($vehicle);

        $driver->setName($driverModel->name);
        $driver->setDocument($driverModel->document);
        $driver->setVehicle($vehicle);
        $entityManager->persist($driver);
        $entityManager->flush();

        return $driver;
    }

    private function checkPlate(DriverModel $driverModel, ?Driver $driver = null): void
    {
        $plateExistent = !$driver || $driver->getVehicle()->getPlate() !== $driverModel->vehiclePlate;
        if ($plateExistent && $this->getDriverRepository()->findByPlate($driverModel->vehiclePlate)
        ) {
            throw new CustomUnprocessableEntityException($this->translator->trans('driver.error.plate_linked'));
        }
    }

    private function checkDocument(DriverModel $driverModel, ?Driver $driver = null): void
    {
        $documentExistent = !$driver || $driver->getDocument() !== $driverModel->document;
        if ($documentExistent && $this->getDriverRepository()->findBy(['document' => $driverModel->document])
        ) {
            throw new CustomUnprocessableEntityException($this->translator->trans('driver.error.document_used'));
        }
    }

    /**
     * @return DriverRepository
     */
    private function getDriverRepository(): ObjectRepository
    {
        return $this->doctrine->getRepository(Driver::class);
    }

    /**
     * @return VehicleRepository
     */
    private function getVehicleRepository(): ObjectRepository
    {
        return $this->doctrine->getRepository(Vehicle::class);
    }
}
