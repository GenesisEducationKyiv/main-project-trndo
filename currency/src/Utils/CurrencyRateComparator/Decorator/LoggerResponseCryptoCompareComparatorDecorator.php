<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Comparator\Decorator;

use App\Utils\CurrencyRateComparator\Comparator\CryptoCompareCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\Logger\CustomLoggerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoggerResponseCryptoCompareComparatorDecorator extends CryptoCompareCurrencyRateComparator
{
    public function __construct(
        private LoggerInterface|CustomLoggerInterface $logger,
        private CryptoCompareCurrencyRateComparator $currencyRateComparator,
        private HttpClientInterface $httpClient,
        private ParameterBagInterface $parameterBag,
    ) {
        parent::__construct($this->httpClient, $this->parameterBag);
    }

    public function compare(Currency $from, Currency $to): float
    {
        $rate = $this->currencyRateComparator->compare($from, $to);

        $this->logger->info('Response - CryptoCompare: '.$rate);

        return $rate;
    }
}
