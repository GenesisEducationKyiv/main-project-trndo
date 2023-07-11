<?php

declare(strict_types=1);

namespace App\Utils\Subscription\Persister;

use App\Utils\FileSystem\Writer\FileSystemWriterInterface;
use App\Utils\Subscription\DataProvider\SubscriptionDataProviderInterface;

class TxtSubscriptionDataPersister implements SubscriptionDataPersisterInterface
{
    public function __construct(
        private FileSystemWriterInterface $fileWriter,
        private SubscriptionDataProviderInterface $dataProvider
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
