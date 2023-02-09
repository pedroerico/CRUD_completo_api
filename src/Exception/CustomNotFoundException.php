<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class CustomNotFoundException extends CustomException
{
    /**
     * @var int
     */
    protected int $status = Response::HTTP_NOT_FOUND;
}
