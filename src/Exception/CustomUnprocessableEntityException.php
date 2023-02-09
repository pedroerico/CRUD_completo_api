<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class CustomUnprocessableEntityException extends CustomException
{
    /**
     * @var int
     */
    protected int $status = Response::HTTP_UNPROCESSABLE_ENTITY;
}
