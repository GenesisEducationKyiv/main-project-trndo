<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\CurrencyRateComparator\Chain;

use App\Utils\CurrencyRateComparator\Chain\AbstractCurrencyRateComparatorHandler;
use App\Utils\CurrencyRateComparator\Chain\CoinGateCurrencyRateComparatorHandler;
use App\Utils\CurrencyRateComparator\Chain\CoinGeckoCurrencyRateComparatorHandler;
use App\Utils\CurrencyRateComparator\Chain\CryptoCompareCurrencyRateComparatorHandler;
use App\Utils\CurrencyRateComparator\Comparator\CoinGateCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Comparator\CoinGeckoCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Comparator\CryptoCompareCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\Exception\ApiRequestException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CurrencyRateComparatorChainTest extends TestCase
{
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * @dataProvider provideCurrencyComparatorChainService
     */
    public function testCompareCallsComparatorWhenSuccessful(string $chain, string $comparator, float $rate): void
    {
        $coinGateComparatorMock = $this->createMock($comparator);
        $coinGateComparatorMock->expects($this->once())->method('compare')->willReturn($rate);

        $handler = new $chain($this->logger, $coinGateComparatorMock);
        $nextComparatorMock = $this->createMock(AbstractCurrencyRateComparatorHandler::class);
        $handler->setNext($nextComparatorMock);

        $result = $handler->execute(Currency::BTC, Currency::UAH);

        $this->assertSame($rate, $result);
    }

    /**
     * @dataProvider provideCurrencyComparatorChainService
     */
    public function testCompareCallsNextComparatorWhenApiRequestException(
        string $chain,
        string $comparator,
        float $rate
    ): void {
        $coinGateComparatorMock = $this->createMock($comparator);
        $coinGateComparatorMock->expects($this->once())
            ->method('compare')
            ->willThrowException(new ApiRequestException('Failed request'));

        $handler = new $chain($this->logger, $coinGateComparatorMock);

        $nextComparatorMock = $this->createMock(AbstractCurrencyRateComparatorHandler::class);
        $nextComparatorMock->expects($this->once())->method('execute')->willReturn($rate);
        $handler->setNext($nextComparatorMock);

        $result = $handler->execute(Currency::BTC, Currency::UAH);

        $this->assertSame($rate, $result);
    }

    /**
     * @dataProvider provideCurrencyComparatorChainService
     */
    public function testCompareThrowsExceptionWhenNoNextComparatorSet(string $chain, string $comparator): void
    {
        $coinGateComparatorMock = $this->createMock($comparator);

        $coinGateComparatorMock->expects($this->once())
            ->method('compare')
            ->willThrowException(new ApiRequestException('Failed request'));

        $handler = new $chain($this->logger, $coinGateComparatorMock);
        $this->expectException(ApiRequestException::class);

        $handler->execute(Currency::BTC, Currency::UAH);
    }

    private function provideCurrencyComparatorChainService(): iterable
    {
        yield [
            CoinGateCurrencyRateComparatorHandler::class,
            CoinGateCurrencyRateComparator::class,
            979094.38,
        ];
        yield [
            CoinGeckoCurrencyRateComparatorHandler::class,
            CoinGeckoCurrencyRateComparator::class,
            1023200.32,
        ];
        yield [
            CryptoCompareCurrencyRateComparatorHandler::class,
            CryptoCompareCurrencyRateComparator::class,
            1222222.33,
        ];
    }
}
