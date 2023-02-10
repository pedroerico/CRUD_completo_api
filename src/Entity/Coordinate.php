<?php

namespace App\Entity;

use App\Repository\CoordinateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoordinateRepository::class)]
#[ORM\HasLifecycleCallbacks]
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
     * @var string|null
     */
    #[ORM\Column]
    private ?string $latitude = null;

    /**
     * @var string|null
     */
    #[ORM\Column]
    private ?string $longitude = null;

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
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     * @return Coordinate
     */
    public function setLatitude(string $latitude): Coordinate
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     * @return Coordinate
     */
    public function setLongitude(string $longitude): Coordinate
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
