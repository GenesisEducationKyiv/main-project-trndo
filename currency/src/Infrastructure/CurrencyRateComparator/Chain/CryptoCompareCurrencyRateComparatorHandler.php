<?php

declare(strict_types=1);

namespace App\Infrastructure\CurrencyRateComparator\Chain;

use App\Application\Logger\CustomLoggerInterface;
use App\Domain\Dictionary\Currency;
use App\Infrastructure\CurrencyRateComparator\Comparator\CryptoCompareCurrencyRateComparator;
use App\Infrastructure\Exception\ApiRequestException;
use Psr\Log\LoggerInterface;

class CryptoCompareCurrencyRateComparatorHandler extends AbstractCurrencyRateComparatorHandler
{
    public function __construct(
        private LoggerInterface|CustomLoggerInterface $logger,
        private CryptoCompareCurrencyRateComparator $cryptoCompareComparator
    ) {
    }

    public function execute(Currency $from, Currency $to): float
    {
        try {
            $rate = $this->cryptoCompareComparator->compare($from, $to);
        } catch (ApiRequestException $exception) {
            $this->logger->error('Failed request to CryptoCompare provider. Message: '.$exception->getMessage());
            $rate = $this->callNext($from, $to);
        }

        return $rate;
    }
}
