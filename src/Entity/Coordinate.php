<?php

namespace App\Entity;

use App\Repository\CoordinateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoordinateRepository::class)]
class Coordinate
{
    use DatetimeTrait;

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $latitude = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $longitude = null;

    /**
     * @var driver|null
     */
    #[ORM\ManyToOne(inversedBy: 'coordinate')]
    private ?driver $driver = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getLatitude(): ?int
    {
        return $this->latitude;
    }

    /**
     * @param int $latitude
     * @return Coordinate
     */
    public function setLatitude(int $latitude): Coordinate
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLongitude(): ?int
    {
        return $this->longitude;
    }

    /**
     * @param int $longitude
     * @return Coordinate
     */
    public function setLongitude(int $longitude): Coordinate
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return driver|null
     */
    public function getDriver(): ?driver
    {
        return $this->driver;
    }

    /**
     * @param driver|null $driver
     * @return Coordinate
     */
    public function setDriver(?driver $driver): Coordinate
    {
        $this->driver = $driver;

        return $this;
    }
}
