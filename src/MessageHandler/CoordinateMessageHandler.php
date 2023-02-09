<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\CoordinateMessage;
use App\service\DriverService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsMessageHandler]
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
            $this->constraintValidator($message->getData());
            $this->driverService->createCoordinate($message->getDriver()->getDocument(), $message->getData());
            $this->logger->info('create');
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function constraintValidator(array $params): void
    {
        $constraint = new Assert\Collection([
            'latitude' => new Assert\Length(['min' => -90, 'max' => 90]),
            'longitude' => new Assert\Length(['min' => -180, 'max' => 180])
        ]);

        $error = $this->validator->validate([
            'latitude' => $params['latitude'],
            'longitude' => $params['longitude'],
        ], $constraint);

        if (count($error)) {
            $this->logger->error($error->get(0)->getMessage());
            throw new \Exception($error->get(0)->getMessage());
        }
    }
}
