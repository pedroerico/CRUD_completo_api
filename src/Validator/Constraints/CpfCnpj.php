<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class CpfCnpj extends Constraint
{
    public bool $cpf = false;
    public bool $cnpj = false;
    public bool $mask = false;
    public string $messageMask = 'O {{ type }} não está em um formato válido.';
    public string $message = 'O {{ type }} informado é inválido.';
}
