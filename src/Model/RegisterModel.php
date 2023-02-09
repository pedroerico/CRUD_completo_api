<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Model to register
 */
class RegisterModel
{
    /**
     * @var string
     */
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    /**
     * @var string
     */
    #[Assert\NotBlank]
    #[Assert\Length(['min' => 6])]
    public string $password;

    /**
     * @var string
     */
    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'password', message: 'auth.error.password_not_match')]
    public string $confirmPassword;
}
