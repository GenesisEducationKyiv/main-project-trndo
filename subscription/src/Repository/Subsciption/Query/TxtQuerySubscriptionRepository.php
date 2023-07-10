<?php

declare(strict_types=1);

namespace App\Repository\Subsciption\Query;

use App\Utils\FileSystem\Reader\FileSystemReaderInterface;

class TxtQuerySubscriptionRepository implements QuerySubscriptionRepositoryInterface
{
    public function __construct(
        private FileSystemReaderInterface $fileReader,
    ) {
    }

    public function getAll(): array
    {
        $content = $this->fileReader->getContents('emails.txt');

        return $content ? explode(',', rtrim($content, ',')) : [];
    }

    public function emailExists(string $email): bool
    {
        $content = $this->getAll();

        return in_array($email, $content, true);
    }
}
