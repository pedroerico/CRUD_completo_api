<?php

namespace App\service;

use App\Entity\User;
use App\Exception\CustomUnprocessableEntityException;
use App\Model\RegisterModel;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{
    /**
     * @param ManagerRegistry $doctrine
     * @param UserPasswordHasherInterface $passwordHasher
     * @param TranslatorInterface $translator
     */
    public function __construct(
        protected ManagerRegistry $doctrine,
        protected UserPasswordHasherInterface $passwordHasher,
        protected TranslatorInterface $translator
    )
    {
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isUserExist(string $email): bool
    {
        return !!$this->getUserByEmail($email);
    }

    /**
     * @param RegisterModel $registerModel
     * @return User
     */
    public function createUser(RegisterModel $registerModel): User
    {
        if ($this->isUserExist($registerModel->email)) {
            throw new CustomUnprocessableEntityException($this->translator->trans('auth.error.user_existent'));
        }

        $user = new User();
        $user->setPassword($this->passwordHasher->hashPassword($user, $registerModel->password));
        $user->setEmail($registerModel->email);

        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        return $user;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
    }

}
