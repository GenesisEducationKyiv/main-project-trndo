<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\FileSystem;

use App\Tests\Unit\Utils\AbstractFileSystemTestCase;
use App\Utils\FileSystem\Reader\FileReader;


class FileReaderTest extends AbstractFileSystemTestCase
{
    private FileReader $fileReader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileReader = new FileReader($this->tempDirectory, $this->filesystem);
        $this->filesystem->mkdir($this->tempDirectory);
    }

    public function testGetContentsReturnsFileContents(): void
    {
        $fileName = 'test.txt';
        $fileContents = 'Hello, World!';

        file_put_contents($this->tempDirectory.'/'.$fileName, $fileContents);

        $contents = $this->fileReader->getContents($fileName);

        self::assertSame($fileContents, $contents);
    }

    public function testGetContentsReturnsNullIfFileDoesNotExist(): void
    {
        $fileName = 'nonexistent.txt';

        $contents = $this->fileReader->getContents($fileName);

        self::assertNull($contents);
    }
}
