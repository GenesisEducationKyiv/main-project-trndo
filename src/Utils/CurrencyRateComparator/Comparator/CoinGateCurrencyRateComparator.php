<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator\Comparator;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorInterface;
use App\Utils\Exception\ApiRequestException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinGateCurrencyRateComparator implements CurrencyRateComparatorInterface
{
    private const REQUEST_URL = '%s/api/v2/rates/merchant/%s/%s';

    public function __construct(
        private HttpClientInterface $httpClient,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @throws ApiRequestException
     */
    public function compare(Currency $from, Currency $to): float
    {
        try {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                sprintf(self::REQUEST_URL, $this->parameterBag->get('coin_gate_host'), $from->value, $to->value)
            );

            $result = json_decode($response->getContent(), true);

        } catch (\Throwable $e) {
            throw new ApiRequestException(message: $e->getMessage());
        }

        if (empty($result)) {
            throw new ApiRequestException('Empty value CoinGate from '.$from->value.' to '.$to->value);
        }

        return $result;
    }
}
