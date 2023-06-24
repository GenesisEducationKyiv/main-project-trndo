<?php

declare(strict_types=1);

namespace App\Tests\Functional\Utils\CurrencyRateComparator;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorInterface;

class TestCurrencyRateComparator implements CurrencyRateComparatorInterface
{
    public function __construct(
        public float $expected = 0.0
    ) {
    }

    public function compare(Currency $from, Currency $to): float
    {
        return $this->expected;
    }
}