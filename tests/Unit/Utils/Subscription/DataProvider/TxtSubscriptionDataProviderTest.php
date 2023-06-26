<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\Subscription\DataProvider;

use App\Tests\Unit\Utils\AbstractFileSystemTestCase;
use App\Utils\FileSystem\Reader\FileReader;
use App\Utils\FileSystem\Reader\FileSystemReaderInterface;
use App\Utils\Subscription\DataProvider\TxtSubscriptionDataProvider;
use Psr\Log\LoggerInterface;

class TxtSubscriptionDataProviderTest extends AbstractFileSystemTestCase
{
    private const EMAILS = ['email1@example.com', 'email2@example.com', 'email3@example.com'];

    private TxtSubscriptionDataProvider $txtDataProvider;
    private FileSystemReaderInterface $fileSystemReader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileSystemReader = $this->createMock(FileSystemReaderInterface::class);
        $this->txtDataProvider = new TxtSubscriptionDataProvider($this->fileSystemReader);
    }

    public function testGetAllReturnsEmptyArrayIfFileNotExists(): void
    {
        $this->fileSystemReader->expects($this->once())->method('getContents')->willReturn(null);

        $emails = $this->txtDataProvider->getAll();

        self::assertEmpty($emails);
    }

    public function testGetAllReturnsEmailsArrayFromFileContents(): void
    {
        $this->fileSystemReader->expects($this->once())->method('getContents')->willReturn(
            $this->getEmailsAsString()
        );

        $emails = $this->txtDataProvider->getAll();

        self::assertSame(self::EMAILS, $emails);
    }

    public function testEmailExistsReturnsFalseIfEmailNotExists(): void
    {
        $email = 'non_existent_email@example.com';
        $this->fileSystemReader->expects($this->once())->method('getContents')->willReturn(
            $this->getEmailsAsString()
        );

        $exists = $this->txtDataProvider->emailExists($email);

        self::assertFalse($exists);
    }

    public function testEmailExistsReturnsTrueEmailExists(): void
    {
        $email = 'email2@example.com';
        $this->fileSystemReader->expects($this->once())->method('getContents')->willReturn($email);

        $exists = $this->txtDataProvider->emailExists($email);

        self::assertTrue($exists);
    }

    private function getEmailsAsString(): string
    {
        return implode(',', self::EMAILS);
    }
}
