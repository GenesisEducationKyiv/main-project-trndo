<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Comparator;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorInterface;
use App\Utils\Exception\ApiRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinGeckoCurrencyRateComparator implements CurrencyRateComparatorInterface
{
    private const REQUEST_URL = 'https://api.coingecko.com/api/v3/simple/price?ids=%s&vs_currencies=%s';

    public function __construct(
        private HttpClientInterface $httpClient
    ) {
    }

    /**
     * @throws ApiRequestException
     */
    public function compare(Currency $from, Currency $to): ?float
    {
        $currencyId = strtolower($from->getFullName());
        $quotedCurrency = strtolower($to->value);

        try {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                sprintf(self::REQUEST_URL, $currencyId, $quotedCurrency)
            );

            $result = json_decode($response->getContent(), true);
        } catch (\Throwable $e) {
            throw new ApiRequestException(message: $e->getMessage());
        }

        return ! empty($result[$currencyId][$quotedCurrency])
            ? $result[$currencyId][$quotedCurrency]
            : null;
    }
}
