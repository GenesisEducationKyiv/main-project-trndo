<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\Subscription\DataPersister;

use App\Tests\Unit\Utils\AbstractFileSystemTestCase;
use App\Utils\FileSystem\FileWriter;
use App\Utils\Subscription\DataProvider\SubscriptionDataProviderInterface;
use App\Utils\Subscription\Persister\TxtSubscriptionDataPersister;
use Psr\Log\LoggerInterface;

class TxtSubscriptionDataPersisterTest extends AbstractFileSystemTestCase
{
    private const FILE_NAME = 'emails.txt';

    private TxtSubscriptionDataPersister $txtDataPersister;
    private SubscriptionDataProviderInterface $dataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $logger = $this->createMock(LoggerInterface::class);
        $fileWriter = new FileWriter($this->tempDirectory, $this->filesystem, $logger);
        $this->dataProvider = $this->createMock(SubscriptionDataProviderInterface::class);
        $this->txtDataPersister = new TxtSubscriptionDataPersister($fileWriter, $this->dataProvider);
    }

    public function testStoreReturnsFalseEmailExists(): void
    {
        $email = 'existing_email@example.com';
        $this->dataProvider->expects($this->once())->method('emailExists')->willReturn(true);

        $result = $this->txtDataPersister->store($email);

        self::assertFalse($result);
    }

    public function testStoreReturnsTrueAndAppendsEmailToFileEmailDoesNotExist(): void
    {
        $email = 'new_email@example.com';
        $this->dataProvider->expects($this->once())->method('emailExists')->willReturn(false);

        $result = $this->txtDataPersister->store($email);

        self::assertTrue($result);
        self::assertSame(
            $email.',',
            file_get_contents($this->tempDirectory.'/'.self::FILE_NAME)
        );
    }
}
