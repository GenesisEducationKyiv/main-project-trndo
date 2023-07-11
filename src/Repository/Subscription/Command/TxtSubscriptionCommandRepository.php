<?php

declare(strict_types=1);

namespace App\Repository\Subscription\Command;

use App\Repository\Subscription\Query\SubscriptionQueryRepositoryInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class TxtSubscriptionCommandRepository implements SubscriptionCommandRepositoryInterface
{
    private const FILE_NAME = 'emails.txt';

    public function __construct(
        private string $directory,
        private Filesystem $filesystem,
        private SubscriptionQueryRepositoryInterface $queryRepository,
    ) {
    }

    public function store(string $email): bool
    {
        if ($this->queryRepository->emailExists($email)) {
            return false;
        }

        return (bool) $this->append($email.',');
    }

    public function append(string $content): string
    {
        $filePath = $this->directory.'/'.self::FILE_NAME;

        try {
            if ( ! $this->filesystem->exists($filePath)) {
                $this->filesystem->dumpFile($filePath, $content);

                return $filePath;
            }

            $this->filesystem->appendToFile($filePath, $content);
        } catch (IOExceptionInterface) {
            throw new \InvalidArgumentException('An error occurred while appending data to the file');
        }

        return $filePath;
    }
}
