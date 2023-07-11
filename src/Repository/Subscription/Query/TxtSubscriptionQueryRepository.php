<?php

declare(strict_types=1);

namespace App\Repository\Subscription\Query;

use Symfony\Component\Filesystem\Filesystem;

class TxtSubscriptionQueryRepository implements SubscriptionQueryRepositoryInterface
{
    private const FILE_NAME = 'emails.txt';

    public function __construct(
        private string $directory,
        private Filesystem $filesystem,
    ) {
    }

    public function getAll(): array
    {
        $content = $this->getContent();

        return $content ? explode(',', rtrim($content, ',')) : [];
    }

    public function emailExists(string $email): bool
    {
        $content = $this->getAll();

        return in_array($email, $content, true);
    }

    private function getContent(): ?string
    {
        $filePath = $this->directory.'/'.self::FILE_NAME;

        if ( ! $this->filesystem->exists($filePath)) {
            return null;
        }

        $contents = file_get_contents($filePath);

        if ( ! $contents) {
            return null;
        }

        return $contents;
    }
}
