<?php

declare(strict_types=1);

namespace App\Tests\Functional\Utils\CurrencyRateComparator;

use App\Utils\CurrencyRateComparator\Currency;

class TestCurrencyRateComparatorHandler extends TestCurrencyRateComparatorChain
{
    public function compare(Currency $from, Currency $to): float
    {
        return parent::compare($from, $to);
    }
}
