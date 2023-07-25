<?php

declare(strict_types=1);

namespace App\Infrastructure\Logger;

use App\Application\Logger\CustomLoggerInterface;
use App\Domain\Message\LoggerMessage;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class RabbitMQLogger implements CustomLoggerInterface
{
    private const NAME = 'rabbitmq';

    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function info(string $message, array $context = []): void
    {
        $this->messageBus->dispatch(
            new LoggerMessage(self::NAME.'.INFO', $message, $context),
            [new AmqpStamp('error')]
        );
    }

    public function debug(string $message, array $context = []): void
    {
        $this->messageBus->dispatch(
            new LoggerMessage(self::NAME.'.DEBUG', $message, $context),
            [new AmqpStamp('info')]
        );
    }

    public function error(string $message, array $context = []): void
    {
        $this->messageBus->dispatch(
            new LoggerMessage(self::NAME.'.ERROR', $message, $context),
            [new AmqpStamp('error')]
        );
    }
}