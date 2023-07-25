<?php

declare(strict_types=1);

namespace App\Domain\Repository\Subscription\Query;

interface SubscriptionQueryRepositoryInterface
{
    public function getAll(): array;

    public function emailExists(string $email): bool;
}
