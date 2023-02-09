<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait created for tables that need updated at and created at columns
 */
trait DatetimeTrait
{
    /**
     * @var \DateTimeImmutable
     */
    #[ORM\Column]
    private DateTimeImmutable $updatedAt;

    /**
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    /**
     * Whe never the entity is called this function is executed
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTimeImmutable());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable $updatedAt
     * @return self
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     * @return self
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
