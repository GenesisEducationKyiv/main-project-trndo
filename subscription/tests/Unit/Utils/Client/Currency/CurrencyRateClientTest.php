<?php

declare(strict_types=1);

namespace Unit\Utils\Client\Currency;

use App\Utils\Client\Currency\CurrencyRateClient;
use App\Utils\Exception\ApiRequestException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class CurrencyRateClientTest extends TestCase
{
    private CurrencyRateClient $currencyRateClient;
    private MockHttpClient $httpClientMock;
    private ParameterBagInterface $parameterBagMock;

    protected function setUp(): void
    {
        $this->httpClientMock = new MockHttpClient();
        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);

        $this->currencyRateClient = new CurrencyRateClient(
            $this->httpClientMock,
            $this->parameterBagMock
        );
    }

    public function testGetRate(): void
    {
        $rate = 1234234.34;

        $this->parameterBagMock->expects($this->once())->method('get')->willReturn('http://example.com');

        $this->httpClientMock->setResponseFactory(new MockResponse(json_encode($rate)));

        $actualRate = $this->currencyRateClient->getRate();

        self::assertSame($rate, $actualRate);
    }

    public function testGetRateThrowsApiRequestException(): void
    {
        $this->parameterBagMock->expects($this->once())->method('get')->willReturn('http://example.com');

        $this->httpClientMock->setResponseFactory(new MockResponse([new \Exception('API request failed')]));

        $this->expectException(ApiRequestException::class);

        $this->currencyRateClient->getRate();
    }
}
