<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository\Subscription\Command;

use App\Domain\Repository\Subscription\Command\SubscriptionCommandRepositoryInterface;
use App\Domain\Repository\Subscription\Query\SubscriptionQueryRepositoryInterface;
use App\Tests\Integration\AbstractFileSystemKernelTestCase;

class TxtCommandSubscriptionRepositoryTest extends AbstractFileSystemKernelTestCase
{
    public function testStoreEmailToTheFileSuccessfully(): void
    {
        $email = 'test_email@mail.com';

        $txtSubscriptionPersister = self::getContainer()->get(SubscriptionCommandRepositoryInterface::class);
        $result = $txtSubscriptionPersister->store($email);

        $txtSubscriptionProvider = self::getContainer()->get(SubscriptionQueryRepositoryInterface::class);

        $this->assertTrue($result);
        $this->assertSame([$email], $txtSubscriptionProvider->getAll());
    }

    public function testStoreEmailToTheFileDuplicate(): void
    {
        $email = 'test_duplicate_email@mail.com';

        $txtSubscriptionPersister = self::getContainer()->get(SubscriptionCommandRepositoryInterface::class);
        $txtSubscriptionPersister->store($email);

        $result = $txtSubscriptionPersister->store($email);

        $txtSubscriptionProvider = self::getContainer()->get(SubscriptionQueryRepositoryInterface::class);

        $this->assertFalse($result);
        $this->assertSame([$email], $txtSubscriptionProvider->getAll());
    }
}
