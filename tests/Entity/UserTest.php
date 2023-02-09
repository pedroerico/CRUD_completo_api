<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private function getUser(): User
    {
        $user = new User();
        $user->setEmail("teste@email.com");
        $user->setPassword("123456");
        $user->setRoles([]);
        return $user;
    }

    public function testCanGetAndSetData(): void
    {

        self::assertSame('teste@email.com', $this->getUser()->getEmail());
        self::assertSame('123456', $this->getUser()->getPassword());
        self::assertSame(['ROLE_USER'], $this->getUser()->getRoles());
    }

    public function testTypes(): void
    {
        self::assertIsString($this->getUser()->getEmail());
        self::assertIsString($this->getUser()->getPassword());
        self::assertIsArray($this->getUser()->getRoles());
    }
}
