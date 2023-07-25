<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CurrencyRateComparator\Comparator;

use App\Application\CurrencyRateComparator\CurrencyRateComparatorInterface;
use App\Domain\Dictionary\Currency;
use App\Infrastructure\CurrencyRateComparator\Comparator\CoinGateCurrencyRateComparator;
use App\Infrastructure\CurrencyRateComparator\Comparator\CoinGeckoCurrencyRateComparator;
use App\Infrastructure\CurrencyRateComparator\Comparator\CryptoCompareCurrencyRateComparator;
use App\Infrastructure\Exception\ApiRequestException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

class CurrencyRateComparatorTest extends TestCase
{
    protected MockHttpClient $httpClient;

    protected ParameterBagInterface $parameterBag;

    protected function setUp(): void
    {
        $this->httpClient = new MockHttpClient();
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
    }

    /**
     * @dataProvider provideCurrencyComparatorService
     */
    public function testCompareReturnsFloatRate(string $comparator, MockResponse $mockResponse, float $rate): void
    {
        $rateComparator = $this->createComparator($comparator, $mockResponse);

        $result = $rateComparator->compare(Currency::BTC, Currency::UAH);

        self::assertSame($rate, $result);
    }

    /**
     * @dataProvider provideCurrencyComparatorService
     */
    public function testCompareThrowsBadRequestHttpException(string $comparator): void
    {
        $errorMessage = 'An error occurred';

        $rateComparator = $this->createComparator($comparator, new MockResponse([new \Exception($errorMessage)]));

        $this->expectException(ApiRequestException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage($errorMessage);

        $rateComparator->compare(Currency::BTC, Currency::UAH);
    }

    private function createComparator(string $comparatorClass, ?MockResponse $response): CurrencyRateComparatorInterface
    {
        if ($response) {
            $this->httpClient->setResponseFactory($response);
        }

        return new $comparatorClass($this->httpClient, $this->parameterBag);
    }

    private function provideCurrencyComparatorService(): iterable
    {
        yield [
            CoinGateCurrencyRateComparator::class,
            new MockResponse(json_encode(979094.38)),
            979094.38,
        ];
        yield [
            CoinGeckoCurrencyRateComparator::class,
            new MockResponse(json_encode(['bitcoin' => ['uah' => 1023200.32]])),
            1023200.32,
        ];
        yield [
            CryptoCompareCurrencyRateComparator::class,
            new MockResponse(json_encode(['UAH' => 1222222.33])),
            1222222.33,
        ];
    }
}
