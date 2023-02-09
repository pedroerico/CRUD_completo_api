<?php

declare(strict_types=1);

namespace App\Message;
use App\Entity\Driver;

final class CoordinateMessage
{
    public function __construct(private readonly Driver $driver, private readonly array $data)
    {
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
