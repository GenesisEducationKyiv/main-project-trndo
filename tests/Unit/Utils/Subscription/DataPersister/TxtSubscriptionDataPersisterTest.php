<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\Subscription\DataPersister;

use App\Tests\Unit\Utils\AbstractFileSystemTestCase;
use App\Utils\FileSystem\Writer\FileSystemWriterInterface;
use App\Utils\Subscription\DataProvider\SubscriptionDataProviderInterface;
use App\Utils\Subscription\Persister\TxtSubscriptionDataPersister;

class TxtSubscriptionDataPersisterTest extends AbstractFileSystemTestCase
{
    private const FILE_NAME = 'emails.txt';

    private TxtSubscriptionDataPersister $txtDataPersister;
    private SubscriptionDataProviderInterface $dataProvider;
    private FileSystemWriterInterface $fileSystemWriter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileSystemWriter = $this->createMock(FileSystemWriterInterface::class);
        $this->dataProvider = $this->createMock(SubscriptionDataProviderInterface::class);
        $this->txtDataPersister = new TxtSubscriptionDataPersister($this->fileSystemWriter, $this->dataProvider);
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
        $this->fileSystemWriter->expects($this->once())->method('appendTo')
            ->willReturn($this->getFullFilePath(self::FILE_NAME));

        $result = $this->txtDataPersister->store($email);

        self::assertTrue($result);
    }
}
