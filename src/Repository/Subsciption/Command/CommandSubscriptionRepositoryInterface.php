<?php

declare(strict_types=1);

namespace App\Repository\Subsciption\Command;

interface CommandSubscriptionRepositoryInterface
{
    public function store(string $email): bool;
}
