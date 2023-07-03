<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository\Subscription\Command;

use App\Repository\Subsciption\Command\TxtCommandSubscriptionRepository;
use App\Repository\Subsciption\Query\QuerySubscriptionRepositoryInterface;
use App\Tests\Unit\Utils\AbstractFileSystemTestCase;
use App\Utils\FileSystem\Writer\FileSystemWriterInterface;

class TxtCommandSubscriptionRepositoryTest extends AbstractFileSystemTestCase
{
    private const FILE_NAME = 'emails.txt';

    private TxtCommandSubscriptionRepository $txtDataPersister;
    private QuerySubscriptionRepositoryInterface $dataProvider;
    private FileSystemWriterInterface $fileSystemWriter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileSystemWriter = $this->createMock(FileSystemWriterInterface::class);
        $this->dataProvider = $this->createMock(QuerySubscriptionRepositoryInterface::class);
        $this->txtDataPersister = new TxtCommandSubscriptionRepository($this->fileSystemWriter, $this->dataProvider);
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
