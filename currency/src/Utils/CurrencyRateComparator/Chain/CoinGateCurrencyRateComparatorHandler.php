<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Chain;

use App\Utils\CurrencyRateComparator\Comparator\CoinGateCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\Exception\ApiRequestException;
use App\Utils\Logger\CustomLoggerInterface;
use Psr\Log\LoggerInterface;

class CoinGateCurrencyRateComparatorHandler extends AbstractCurrencyRateComparatorHandler
{
    public function __construct(
        private LoggerInterface|CustomLoggerInterface $logger,
        private CoinGateCurrencyRateComparator $coinGateComparator
    ) {
    }

    public function execute(Currency $from, Currency $to): float
    {
        try {
            $rate = $this->coinGateComparator->compare($from, $to);
        } catch (ApiRequestException $exception) {
            $this->logger->error('Failed request to CoinGate provider. Message: '.$exception->getMessage());
            $rate = $this->callNext($from, $to);
        }

        return $rate;
    }
}
