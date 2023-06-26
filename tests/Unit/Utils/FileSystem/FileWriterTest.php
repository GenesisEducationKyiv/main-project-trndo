<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\FileSystem;

use App\Tests\Unit\Utils\AbstractFileSystemTestCase;
use App\Utils\FileSystem\Writer\FileWriter;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class FileWriterTest extends AbstractFileSystemTestCase
{
    private const TEST_FILE = 'test_file.txt';

    private FileWriter $fileWriter;
    private Filesystem $filesystemMock;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->fileWriter = new FileWriter($this->tempDirectory, $this->filesystem, $this->logger);
        $this->filesystemMock = $this->createMock(Filesystem::class);
    }

    public function testWriteToCreatesFileWithContent(): void
    {
        $fileName = self::TEST_FILE;
        $content = 'Test content 1';

        $filePath = $this->fileWriter->writeTo($fileName, $content);

        self::assertFileExists($filePath);
        self::assertSame($content, file_get_contents($filePath));
    }

    public function testWriteToThrowsExceptionOnFailure(): void
    {
        $fileName = self::TEST_FILE;
        $content = 'Test content 2';

        $this->filesystemMock->expects(self::once())
            ->method('dumpFile')
            ->willThrowException(new IOException('Test exception'));

        $fileWriter = new FileWriter($this->tempDirectory, $this->filesystemMock, $this->logger);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An error occurred while creating the file');

        $fileWriter->writeTo($fileName, $content);
    }

    public function testAppendToWritesContentToFile(): void
    {
        $fileName = self::TEST_FILE;
        $initialContent = 'Initial content';
        $appendedContent = 'Appended content';
        $filePath = $this->tempDirectory.'/'.$fileName;
        file_put_contents($filePath, $initialContent);

        $filePath = $this->fileWriter->appendTo($fileName, $appendedContent);

        self::assertSame($initialContent.$appendedContent, file_get_contents($filePath));
    }

    public function testAppendToWritesNewFileIfNotExists(): void
    {
        $fileName = self::TEST_FILE;
        $content = 'New file content';

        $filePath = $this->fileWriter->appendTo($fileName, $content);

        self::assertFileExists($filePath);
        self::assertSame($content, file_get_contents($filePath));
    }

    public function testAppendToThrowsExceptionOnFailure(): void
    {
        $fileName = self::TEST_FILE;
        $content = 'Test content';

        $this->filesystemMock->expects(self::once())->method('exists')->willReturn(true);
        $this->filesystemMock->expects(self::once())
            ->method('appendToFile')
            ->willThrowException(new IOException('Test exception'));

        $fileWriter = new FileWriter($this->tempDirectory, $this->filesystemMock, $this->logger);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An error occurred while appending data to the file');

        $fileWriter->appendTo($fileName, $content);
    }
}
