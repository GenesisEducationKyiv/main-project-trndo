<?php

declare(strict_types=1);

namespace App\Utils\FileSystem\Reader;

interface FileSystemReaderInterface
{
    public function getContents(string $fileName): ?string;
}
