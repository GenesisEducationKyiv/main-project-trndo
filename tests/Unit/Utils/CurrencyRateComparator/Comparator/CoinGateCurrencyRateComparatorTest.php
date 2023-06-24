<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\CurrencyRateComparator\Comparator;

use App\Utils\CurrencyRateComparator\Comparator\CoinGateCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\Exception\ApiRequestException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

class CoinGateCurrencyRateComparatorTest extends TestCase
{
    protected CoinGateCurrencyRateComparator $rateComparator;

    protected MockHttpClient $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new MockHttpClient();
        $this->rateComparator = new CoinGateCurrencyRateComparator($this->httpClient);
    }

    public function testCompareReturnsFloatRate(): void
    {
        $rate = 979094.38;

        $mockResponse = new MockResponse(json_encode($rate));
        $this->httpClient->setResponseFactory($mockResponse);

        $result = $this->rateComparator->compare(Currency::BTC, Currency::UAH);

        self::assertSame($rate, $result);
    }

    public function testCompareThrowsBadRequestHttpException(): void
    {
        $errorMessage = 'An error occurred';

        $mockResponse = new MockResponse([new \Exception($errorMessage)]);
        $this->httpClient->setResponseFactory($mockResponse);

        $this->expectException(ApiRequestException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage($errorMessage);

        $this->rateComparator->compare(Currency::BTC, Currency::UAH);
    }
}
