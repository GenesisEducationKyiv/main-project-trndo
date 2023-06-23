<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\Subscription\DataProvider;

use App\Tests\Unit\Utils\AbstractFileSystemTestCase;
use App\Utils\FileSystem\FileReader;
use App\Utils\Subscription\DataProvider\TxtSubscriptionDataProvider;
use Psr\Log\LoggerInterface;

class TxtSubscriptionDataProviderTest extends AbstractFileSystemTestCase
{
    private const EMAILS = ['email1@example.com', 'email2@example.com', 'email3@example.com'];
    private const FILE_NAME = 'emails.txt';

    private TxtSubscriptionDataProvider $txtDataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $logger = $this->createMock(LoggerInterface::class);
        $fileReader = new FileReader($this->tempDirectory, $this->filesystem, $logger);
        $this->txtDataProvider = new TxtSubscriptionDataProvider($fileReader);
    }

    public function testGetAllReturnsEmptyArrayIfFileNotExists(): void
    {
        $emails = $this->txtDataProvider->getAll();

        $this->assertEmpty($emails);
    }

    public function testIfEmailExistsReturnsFalseIfEmailNotExists(): void
    {
        $email = 'non_existent_email@example.com';

        $exists = $this->txtDataProvider->ifEmailExists($email);

        $this->assertFalse($exists);
    }

    public function testGetAllReturnsEmailsArrayFromFileContents(): void
    {
        $fileName = self::FILE_NAME;
        $content = implode(',', self::EMAILS);
        $filePath = $this->tempDirectory.'/'.$fileName;
        file_put_contents($filePath, $content);

        $emails = $this->txtDataProvider->getAll();

        $this->assertSame(self::EMAILS, $emails);
    }

    public function testIfEmailExistsReturnsTrueIfEmailExists(): void
    {
        $fileName = 'emails.txt';
        $content = implode(',', self::EMAILS);
        $filePath = $this->tempDirectory.'/'.$fileName;
        file_put_contents($filePath, $content);

        $email = 'email2@example.com';

        $exists = $this->txtDataProvider->ifEmailExists($email);

        $this->assertTrue($exists);
    }
}
