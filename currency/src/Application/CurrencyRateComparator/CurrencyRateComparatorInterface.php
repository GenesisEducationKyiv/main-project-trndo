<?php

declare(strict_types=1);

namespace App\Application\CurrencyRateComparator;

use App\Domain\Dictionary\Currency;

interface CurrencyRateComparatorInterface
{
    public function compare(Currency $from, Currency $to): float;
}
