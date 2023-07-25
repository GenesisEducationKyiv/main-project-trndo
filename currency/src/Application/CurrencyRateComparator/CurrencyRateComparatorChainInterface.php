<?php

declare(strict_types=1);

namespace App\Application\CurrencyRateComparator;

use App\Domain\Dictionary\Currency;

interface CurrencyRateComparatorChainInterface
{
    public function execute(Currency $from, Currency $to): float;

    public function setNext(CurrencyRateComparatorChainInterface $next): CurrencyRateComparatorChainInterface;
}
