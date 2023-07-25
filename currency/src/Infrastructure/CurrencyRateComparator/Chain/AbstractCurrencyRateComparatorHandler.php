<?php

declare(strict_types=1);

namespace App\Infrastructure\CurrencyRateComparator\Chain;

use App\Application\CurrencyRateComparator\CurrencyRateComparatorChainInterface;
use App\Domain\Dictionary\Currency;
use App\Infrastructure\Exception\ApiRequestException;

abstract class AbstractCurrencyRateComparatorHandler implements CurrencyRateComparatorChainInterface
{
    protected ?CurrencyRateComparatorChainInterface $next = null;

    /**
     * @throws ApiRequestException
     */
    public function execute(Currency $from, Currency $to): float
    {
        return $this->callNext($from, $to);
    }

    public function setNext(CurrencyRateComparatorChainInterface $next): CurrencyRateComparatorChainInterface
    {
        $this->next = $next;

        return $next;
    }

    protected function callNext(Currency $from, Currency $to): float
    {
        if ( ! $this->next) {
            throw new ApiRequestException('Failed to fetch currency rate');
        }

        return $this->next->execute($from, $to);
    }
}
