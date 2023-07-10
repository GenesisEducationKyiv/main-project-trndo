<?php

declare(strict_types=1);

namespace App\Tests\Functional\Presentation\Controller\Api;

use App\Tests\Functional\AbstractApiTestCase;
use App\Tests\Functional\Utils\CurrencyRateComparator\TestCurrencyRateComparatorChain;
use App\Tests\Functional\Utils\CurrencyRateComparator\TestCurrencyRateComparatorHandler;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorChainInterface;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class CurrencyRateControllerTest extends AbstractApiTestCase
{
    public function testExpects200(): void
    {
        $rate = 999123.23;
        $chain = self::getContainer()->get(TestCurrencyRateComparatorChain::class);
        $chain->expectedRate = $rate;

        $result = self::httpGet('/api/rate');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame($rate, json_decode($result, true));
    }
}
