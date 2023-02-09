<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DriverRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Driver
{
    use DatetimeTrait;

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show'])]
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
    #[ORM\Column(length: 14, unique: true)]
    #[Groups(['show'])]
    private string $document;

    /**
     * @var Vehicle|null
     */
    #[ORM\ManyToOne(inversedBy: 'driver')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show'])]
    private ?Vehicle $vehicle = null;

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
     * @return Driver
     */
    public function setName(string $name): Driver
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocument(): string
    {
        return $this->document;
    }

    /**
     * @param string $document
     * @return Driver
     */
    public function setDocument(string $document): Driver
    {
        $this->document = preg_replace('/[^0-9]/', '', $document);

        return $this;
    }

    /**
     * @return Vehicle|null
     */
    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle|null $vehicle
     * @return Driver
     */
    public function setVehicle(?Vehicle $vehicle): Driver
    {
        $this->vehicle = $vehicle;

        return $this;
    }
}
