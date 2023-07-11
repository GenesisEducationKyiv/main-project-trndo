<?php

declare(strict_types=1);

namespace App\Tests\Functional\Utils;

use App\Utils\Client\Currency\CurrencyClientInterface;

class TestCurrencyClient implements CurrencyClientInterface
{
    public function __construct(public float $expected = 0.0)
    {
    }

    public function getRate(): float
    {
        return $this->expected;
    }
}
