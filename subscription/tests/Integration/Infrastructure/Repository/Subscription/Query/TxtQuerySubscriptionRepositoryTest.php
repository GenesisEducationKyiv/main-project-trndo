<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository\Subscription\Query;


use App\Domain\Repository\Subscription\Query\SubscriptionQueryRepositoryInterface;
use App\Tests\Integration\AbstractFileSystemKernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class TxtQuerySubscriptionRepositoryTest extends AbstractFileSystemKernelTestCase
{
    private const FILE_NAME = 'emails.txt';

    public function testReadSubscriptionDataFromFile(): void
    {
        $email = 'test_email@mail.com';
        $this->addEmailToFile($email);

        $txtSubscriptionProvider = self::getContainer()->get(SubscriptionQueryRepositoryInterface::class);

        $this->assertSame([$email], $txtSubscriptionProvider->getAll());
    }

    public function testIsEmailIsExistsInFileReturnsTrue(): void
    {
        $email = 'test_existing_email@mail.com';
        $this->addEmailToFile($email);

        $txtSubscriptionProvider = self::getContainer()->get(SubscriptionQueryRepositoryInterface::class);

        $this->assertTrue($txtSubscriptionProvider->emailExists($email));
    }

    public function testIsEmailIsExistsInFileReturnsFalse(): void
    {
        $email = 'non_existing_email@mail.com';

        $txtSubscriptionProvider = self::getContainer()->get(SubscriptionQueryRepositoryInterface::class);

        $this->assertFalse($txtSubscriptionProvider->emailExists($email));
    }

    public function testReadSubscriptionDataFromNotExistingFile(): void
    {
        $txtSubscriptionProvider = self::getContainer()->get(SubscriptionQueryRepositoryInterface::class);

        $this->assertSame([], $txtSubscriptionProvider->getAll());
    }

    private function addEmailToFile(string $email): void
    {
        $dir = self::$kernel->getProjectDir();
        $filesystem = new Filesystem();
        $filesystem->dumpFile($dir.'/test_system/emails.txt', $email);
    }
}
