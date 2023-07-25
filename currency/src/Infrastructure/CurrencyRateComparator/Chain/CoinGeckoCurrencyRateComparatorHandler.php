<?php

declare(strict_types=1);

namespace App\Infrastructure\CurrencyRateComparator\Chain;

use App\Application\Logger\CustomLoggerInterface;
use App\Domain\Dictionary\Currency;
use App\Infrastructure\CurrencyRateComparator\Comparator\CoinGeckoCurrencyRateComparator;
use App\Infrastructure\Exception\ApiRequestException;
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
