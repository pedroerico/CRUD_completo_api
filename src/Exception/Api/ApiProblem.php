<?php

namespace App\Exception\Api;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 */
class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';

    private static array $titles = [
        self::TYPE_VALIDATION_ERROR => 'There was a validation error',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
    ];

    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var string|null
     */
    private ?string $type;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var array
     */
    private array $extraData = [];

    public function __construct(int $statusCode, ?string $type = null)
    {
        if ($type === null) {
            $type = 'about:blank';
            $title = Response::$statusTexts[$statusCode] ?? 'Unknown status code';
        } else {
            if (!isset(self::$titles[$type])) {
                throw new InvalidArgumentException('No title for type ' . $type);
            }
            $title = self::$titles[$type];
        }

        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->title = $title;
    }

    public function toArray(): array
    {
        return array_merge(
            $this->extraData,
            [
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            ]
        );
    }

    public function set($name, $value): void
    {
        $this->extraData[$name] = $value;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
