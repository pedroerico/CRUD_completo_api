<?php

namespace App\DataFixtures\Processor;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Create a user in the database to be used as a test(PHPUnit)
 */
final class UserProcessor implements ProcessorInterface
{
    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(protected readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @inheritdoc
     */
    public function preProcess(string $id, object $object): void
    {
        if (false === $object instanceof User) {
            return;
        }

        $object->setEmail('admin@hotmail.com');
        $object->setPassword($this->passwordHasher->hashPassword($object, '123456'));
    }

    /**
     * @inheritdoc
     */
    public function postProcess(string $id, object $object): void
    {
        // do nothing
    }
}
