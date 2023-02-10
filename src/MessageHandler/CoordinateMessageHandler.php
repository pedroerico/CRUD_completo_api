<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\CoordinateMessage;
use App\service\DriverService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CoordinateMessageHandler implements MessageHandlerInterface
{

    public function __construct(
        protected readonly DriverService $driverService,
        protected readonly LoggerInterface $logger,
        protected readonly ValidatorInterface $validator
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CoordinateMessage $message): void
    {
        try {
            $this->logger->info('received');
            $this->validateCoordinate($message->getData());
            $this->driverService->createCoordinate($message->getDriver()->getDocument(), $message->getData());
            $this->logger->info('create');
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function validateCoordinate(array $params): void
    {
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];
        if ($latitude >= 90 || $latitude <= -90 || $longitude <= -180 || $longitude >= 180 ) {
            $errorMsg = 'invalid coordinates';
            $this->logger->error($errorMsg);
            throw new \Exception($errorMsg);
        }
    }
}
