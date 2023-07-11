<?php

declare(strict_types=1);

namespace App\Tests\Functional\Utils;

use App\Utils\Client\Subscription\SubscriptionClientInterface;

class TestSubscriptionClient implements SubscriptionClientInterface
{
    public function __construct(public array $expected = [])
    {
    }

    public function getSubscribers(): array
    {
        return $this->expected;
    }
}
