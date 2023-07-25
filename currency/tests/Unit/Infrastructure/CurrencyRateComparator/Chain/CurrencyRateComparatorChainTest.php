<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CurrencyRateComparator\Chain;

use App\Domain\Dictionary\Currency;
use App\Infrastructure\CurrencyRateComparator\Chain\AbstractCurrencyRateComparatorHandler;
use App\Infrastructure\CurrencyRateComparator\Chain\CoinGateCurrencyRateComparatorHandler;
use App\Infrastructure\CurrencyRateComparator\Chain\CoinGeckoCurrencyRateComparatorHandler;
use App\Infrastructure\CurrencyRateComparator\Chain\CryptoCompareCurrencyRateComparatorHandler;
use App\Infrastructure\CurrencyRateComparator\Comparator\CoinGateCurrencyRateComparator;
use App\Infrastructure\CurrencyRateComparator\Comparator\CoinGeckoCurrencyRateComparator;
use App\Infrastructure\CurrencyRateComparator\Comparator\CryptoCompareCurrencyRateComparator;
use App\Infrastructure\Exception\ApiRequestException;
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
