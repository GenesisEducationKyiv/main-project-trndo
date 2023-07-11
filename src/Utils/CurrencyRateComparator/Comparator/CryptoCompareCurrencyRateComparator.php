<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Comparator;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorInterface;
use App\Utils\Exception\ApiRequestException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CryptoCompareCurrencyRateComparator implements CurrencyRateComparatorInterface
{
    private const REQUEST_URL = '%s/data/price?fsym=%s&tsyms=%s';

    public function __construct(
        private HttpClientInterface $httpClient,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function compare(Currency $from, Currency $to): float
    {
        try {
            $host = (string) $this->parameterBag->get('crypto_compare_host');
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                sprintf(self::REQUEST_URL, $host, $from->value, $to->value)
            );

            $result = json_decode($response->getContent(), true);
        } catch (\Throwable $e) {
            throw new ApiRequestException(message: $e->getMessage());
        }

        $rate = $result[$to->value] ?? null;
        if ( ! $rate) {
            throw new ApiRequestException('Empty value CryptoCompare from '.$from->value.' to '.$to->value);
        }

        return $rate;
    }
}
