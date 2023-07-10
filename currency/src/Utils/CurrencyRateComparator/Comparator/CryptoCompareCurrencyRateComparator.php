<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Comparator;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorInterface;
use App\Utils\Exception\ApiRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CryptoCompareCurrencyRateComparator implements CurrencyRateComparatorInterface
{
    private const REQUEST_URL = 'https://min-api.cryptocompare.com/data/price?fsym=%s&tsyms=%s';

    public function __construct(
        private HttpClientInterface $httpClient
    ) {
    }

    public function compare(Currency $from, Currency $to): ?float
    {
        try {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                sprintf(self::REQUEST_URL, $from->value, $to->value)
            );

            $result = json_decode($response->getContent(), true);
        } catch (\Throwable $e) {
            throw new ApiRequestException(message: $e->getMessage());
        }

        return ! empty($result[$to->value]) ? (float) $result[$to->value] : null;
    }
}
