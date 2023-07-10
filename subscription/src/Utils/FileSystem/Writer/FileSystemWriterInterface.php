<?php

declare(strict_types=1);

namespace App\Utils\FileSystem\Writer;

interface FileSystemWriterInterface
{
    public function writeTo(string $fileName, string $content): string;

    public function appendTo(string $fileName, string $content): string;
}
