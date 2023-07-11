<?php

declare(strict_types=1);

namespace App\Repository\Subscription\Command;

interface SubscriptionCommandRepositoryInterface
{
    public function store(string $email): bool;
}
