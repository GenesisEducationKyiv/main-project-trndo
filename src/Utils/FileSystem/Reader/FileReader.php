<?php

declare(strict_types=1);

namespace App\Utils\FileSystem\Reader;

use Symfony\Component\Filesystem\Filesystem;

class FileReader implements FileSystemReaderInterface
{
    public function __construct(
        private string $directory,
        private Filesystem $filesystem,
    ) {
    }

    public function getContents(string $fileName): ?string
    {
        $filePath = $this->directory.'/'.$fileName;

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
