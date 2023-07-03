<?php

declare(strict_types=1);

namespace App\Tests\Functional\Utils\CurrencyRateComparator;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorChainInterface;

class TestCurrencyRateComparatorChain implements CurrencyRateComparatorChainInterface
{
    public CurrencyRateComparatorChainInterface $next;

    public function __construct(
        public float $expectedRate = 0.0
    ) {
    }

    public function compare(Currency $from, Currency $to): float
    {
        return $this->expectedRate;
    }

    public function setNext(CurrencyRateComparatorChainInterface $next): CurrencyRateComparatorChainInterface
    {
        $this->next = $next;

        return $next;
    }
}
