<?php

declare(strict_types=1);

namespace App\Tests\Integration\Utils\Subscription\DataProvider;

use App\Tests\Integration\AbstractFileSystemKernelTestCase;
use App\Utils\FileSystem\FileWriter;
use App\Utils\Subscription\DataProvider\TxtSubscriptionDataProvider;

class TxtSubscriptionDataProviderTest extends AbstractFileSystemKernelTestCase
{
    private const FILE_NAME = 'emails.txt';

    public function testReadSubscriptionDataFromFile(): void
    {
        $email = 'test_email@mail.com';
        $this->addEmailToFile($email);

        $txtSubscriptionProvider = self::getContainer()->get(TxtSubscriptionDataProvider::class);

        $this->assertSame([$email], $txtSubscriptionProvider->getAll());
    }

    public function testIsEmailIsExistsInFileReturnsTrue(): void
    {
        $email = 'test_existing_email@mail.com';
        $this->addEmailToFile($email);

        $txtSubscriptionProvider = self::getContainer()->get(TxtSubscriptionDataProvider::class);

        $this->assertTrue($txtSubscriptionProvider->emailExists($email));
    }

    public function testIsEmailIsExistsInFileReturnsFalse(): void
    {
        $email = 'non_existing_email@mail.com';

        $txtSubscriptionProvider = self::getContainer()->get(TxtSubscriptionDataProvider::class);

        $this->assertFalse($txtSubscriptionProvider->emailExists($email));
    }

    public function testReadSubscriptionDataFromNotExistingFile(): void
    {
        $txtSubscriptionProvider = self::getContainer()->get(TxtSubscriptionDataProvider::class);

        $this->assertSame([], $txtSubscriptionProvider->getAll());
    }

    private function addEmailToFile(string $email): void
    {
        $fileWriter = self::getContainer()->get(FileWriter::class);
        $fileWriter->writeTo(self::FILE_NAME, $email);
    }
}
