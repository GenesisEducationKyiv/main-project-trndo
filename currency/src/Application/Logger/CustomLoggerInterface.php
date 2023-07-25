<?php

declare(strict_types=1);

namespace App\Application\Logger;

interface CustomLoggerInterface
{
    public function info(string $message, array $context = []): void;

    public function debug(string $message, array $context = []): void;

    public function error(string $message, array $context = []): void;
}
