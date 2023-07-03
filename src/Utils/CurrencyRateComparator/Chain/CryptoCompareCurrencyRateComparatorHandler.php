<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Chain;

use App\Utils\CurrencyRateComparator\Comparator\CryptoCompareCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\Exception\ApiRequestException;
use Psr\Log\LoggerInterface;

class CryptoCompareCurrencyRateComparatorHandler extends AbstractCurrencyRateComparatorHandler
{
    public function __construct(
        private LoggerInterface $logger,
        private CryptoCompareCurrencyRateComparator $coinGateComparator
    ) {
    }

    public function compare(Currency $from, Currency $to): float
    {
        try {
            $rate = $this->coinGateComparator->compare($from, $to);
        } catch (ApiRequestException $exception) {
            $this->logger->info('Failed request to CryptoCompare provider. Message: '.$exception->getMessage());
            $rate = parent::compare($from, $to);
        }

        return $rate ?? parent::compare($from, $to);
    }
}
