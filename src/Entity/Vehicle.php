<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Vehicle
{
    use DatetimeTrait;

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(length: 255)]
    #[Groups(['show'])]
    private string $name;

    /**
     * @var string
     */
    #[ORM\Column(length: 7, unique: true)]
    #[Groups(['show'])]
    private string $plate;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show'])]
    private ?string $color = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'vehicle', targetEntity: driver::class)]
    private Collection|ArrayCollection $driver;

    public function __construct()
    {
        $this->driver = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Vehicle
     */
    public function setName(string $name): Vehicle
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlate(): string
    {
        return $this->plate;
    }

    /**
     * @param string $plate
     * @return Vehicle
     */
    public function setPlate(string $plate): Vehicle
    {
        $this->plate = $plate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     * @return Vehicle
     */
    public function setColor(?string $color): Vehicle
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, driver>
     */
    public function getDriver(): Collection
    {
        return $this->driver;
    }

    /**
     * @param Driver $driver
     * @return Vehicle
     */
    public function addDriver(Driver $driver): Vehicle
    {
        if (!$this->driver->contains($driver)) {
            $this->driver->add($driver);
            $driver->setVehicle($this);
        }

        return $this;
    }

    /**
     * @param Driver $driver
     * @return Vehicle
     */
    public function removeDriver(Driver $driver): Vehicle
    {
        if ($this->driver->removeElement($driver)) {
            // set the owning side to null (unless already changed)
            if ($driver->getVehicle() === $this) {
                $driver->setVehicle(null);
            }
        }

        return $this;
    }
}
