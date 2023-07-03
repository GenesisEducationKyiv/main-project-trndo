<?php

declare(strict_types=1);

namespace App\Repository\Subsciption\Query;

interface QuerySubscriptionRepositoryInterface
{
    public function getAll(): array;

    public function emailExists(string $email): bool;
}
