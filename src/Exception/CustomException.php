<?php

namespace App\Exception;

use LogicException;
use Symfony\Component\HttpFoundation\Response;

class CustomException extends LogicException
{
    /**
     * @var int
     */
    protected int $status = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
