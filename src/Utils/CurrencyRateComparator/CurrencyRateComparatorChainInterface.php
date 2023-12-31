<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator;

interface CurrencyRateComparatorChainInterface
{
    public function execute(Currency $from, Currency $to): float;

    public function setNext(CurrencyRateComparatorChainInterface $next): CurrencyRateComparatorChainInterface;
}
