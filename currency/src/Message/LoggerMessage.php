<?php

declare(strict_types=1);

namespace App\Message;

class LoggerMessage
{
    public function __construct(
        private string $channel,
        private string $message,
        private array $context = []
    ) {
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
