<?php

declare(strict_types=1);

namespace App\Utils\Client\Currency;

use App\Utils\Exception\ApiRequestException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyRateClient implements CurrencyClientInterface
{
    private const RATE_URI = '%s/api/rate';

    public function __construct(
        private HttpClientInterface $httpClient,
        private ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @throws ApiRequestException
     */
    public function getRate(): float
    {
        try {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                sprintf(self::RATE_URI, (string) $this->parameterBag->get('currency_service_host')),
            );

            $rate = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            throw new ApiRequestException($exception->getMessage());
        }

        return $rate;
    }
}
