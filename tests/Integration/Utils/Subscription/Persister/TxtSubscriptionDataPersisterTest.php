<?php

declare(strict_types=1);

namespace App\Tests\Integration\Utils\Subscription\Persister;

use App\Tests\Integration\AbstractFileSystemKernelTestCase;
use App\Utils\Subscription\DataProvider\TxtSubscriptionDataProvider;
use App\Utils\Subscription\Persister\TxtSubscriptionDataPersister;

class TxtSubscriptionDataPersisterTest extends AbstractFileSystemKernelTestCase
{
    public function testStoreEmailToTheFileSuccessfully(): void
    {
        $email = 'test_email@mail.com';

        $txtSubscriptionPersister = self::getContainer()->get(TxtSubscriptionDataPersister::class);
        $result = $txtSubscriptionPersister->store($email);

        $txtSubscriptionProvider = self::getContainer()->get(TxtSubscriptionDataProvider::class);

        $this->assertTrue($result);
        $this->assertSame([$email], $txtSubscriptionProvider->getAll());
    }

    public function testStoreEmailToTheFileDuplicate(): void
    {
        $email = 'test_duplicate_email@mail.com';

        $txtSubscriptionPersister = self::getContainer()->get(TxtSubscriptionDataPersister::class);
        $txtSubscriptionPersister->store($email);

        $result = $txtSubscriptionPersister->store($email);

        $txtSubscriptionProvider = self::getContainer()->get(TxtSubscriptionDataProvider::class);

        $this->assertFalse($result);
        $this->assertSame([$email], $txtSubscriptionProvider->getAll());
    }
}
