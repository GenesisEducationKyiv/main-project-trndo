<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Chain;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorChainInterface;
use App\Utils\Exception\ApiRequestException;

abstract class AbstractCurrencyRateComparatorHandler implements CurrencyRateComparatorChainInterface
{
    protected ?CurrencyRateComparatorChainInterface $next = null;

    public function compare(Currency $from, Currency $to): float
    {
        if (!$this->next) {
            throw new ApiRequestException('Failed to fetch currency rate');
        }

        return $this->next->compare($from, $to);
    }

    public function setNext(CurrencyRateComparatorChainInterface $next): CurrencyRateComparatorChainInterface
    {
        $this->next = $next;

        return $next;
    }
}