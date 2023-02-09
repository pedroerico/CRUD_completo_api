<?php

namespace App\Exception\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiProblemException extends HttpException
{
    /**
     * @param ApiProblem $apiProblem
     * @param \Exception|null $previous
     * @param array $headers
     * @param $code
     */
    public function __construct(
        private readonly ApiProblem $apiProblem,
        \Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * @return ApiProblem
     */
    public function getApiProblem(): ApiProblem
    {
        return $this->apiProblem;
    }
}
