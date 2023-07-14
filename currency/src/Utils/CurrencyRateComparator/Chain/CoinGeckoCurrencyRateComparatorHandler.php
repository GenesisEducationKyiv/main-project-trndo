<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Chain;

use App\Utils\CurrencyRateComparator\Comparator\CoinGeckoCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\Exception\ApiRequestException;
use App\Utils\Logger\CustomLoggerInterface;
use Psr\Log\LoggerInterface;

class CoinGeckoCurrencyRateComparatorHandler extends AbstractCurrencyRateComparatorHandler
{
    public function __construct(
        private LoggerInterface|CustomLoggerInterface $logger,
        private CoinGeckoCurrencyRateComparator $coinGeckoComparator
    ) {
    }

    public function execute(Currency $from, Currency $to): float
    {
        try {
            $rate = $this->coinGeckoComparator->compare($from, $to);
        } catch (ApiRequestException $exception) {
            $this->logger->error('Failed request to CoinGecko provider. Message: '.$exception->getMessage());
            $rate = $this->callNext($from, $to);
        }

        return $rate;
    }
}
