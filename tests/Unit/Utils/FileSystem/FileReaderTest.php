<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\FileSystem;

use App\Tests\Unit\Utils\AbstractFileSystemTestCase;
use App\Utils\FileSystem\FileReader;
use Psr\Log\LoggerInterface;

class FileReaderTest extends AbstractFileSystemTestCase
{
    private FileReader $fileReader;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->fileReader = new FileReader($this->tempDirectory, $this->filesystem, $this->logger);
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
