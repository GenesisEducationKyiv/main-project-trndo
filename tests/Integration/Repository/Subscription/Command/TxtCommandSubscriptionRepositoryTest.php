<?php

namespace App\Tests\Integration\Repository\Subscription\Command;

use App\Repository\Subsciption\Command\TxtCommandSubscriptionRepository;
use App\Repository\Subsciption\Query\TxtQuerySubscriptionRepository;
use App\Tests\Integration\AbstractFileSystemKernelTestCase;

class TxtCommandSubscriptionRepositoryTest extends AbstractFileSystemKernelTestCase
{
    public function testStoreEmailToTheFileSuccessfully(): void
    {
        $email = 'test_email@mail.com';

        $txtSubscriptionPersister = self::getContainer()->get(TxtCommandSubscriptionRepository::class);
        $result = $txtSubscriptionPersister->store($email);

        $txtSubscriptionProvider = self::getContainer()->get(TxtQuerySubscriptionRepository::class);

        $this->assertTrue($result);
        $this->assertSame([$email], $txtSubscriptionProvider->getAll());
    }

    public function testStoreEmailToTheFileDuplicate(): void
    {
        $email = 'test_duplicate_email@mail.com';

        $txtSubscriptionPersister = self::getContainer()->get(TxtCommandSubscriptionRepository::class);
        $txtSubscriptionPersister->store($email);

        $result = $txtSubscriptionPersister->store($email);

        $txtSubscriptionProvider = self::getContainer()->get(TxtQuerySubscriptionRepository::class);

        $this->assertFalse($result);
        $this->assertSame([$email], $txtSubscriptionProvider->getAll());
    }
}