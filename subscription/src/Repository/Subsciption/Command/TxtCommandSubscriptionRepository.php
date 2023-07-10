<?php

declare(strict_types=1);

namespace App\Repository\Subsciption\Command;

use App\Repository\Subsciption\Query\QuerySubscriptionRepositoryInterface;
use App\Utils\FileSystem\Writer\FileSystemWriterInterface;

class TxtCommandSubscriptionRepository implements CommandSubscriptionRepositoryInterface
{
    public function __construct(
        private FileSystemWriterInterface $fileWriter,
        private QuerySubscriptionRepositoryInterface $dataProvider
    ) {
    }

    public function store(string $email): bool
    {
        if ($this->dataProvider->emailExists($email)) {
            return false;
        }

        return (bool) $this->fileWriter->appendTo('emails.txt', $email.',');
    }
}
