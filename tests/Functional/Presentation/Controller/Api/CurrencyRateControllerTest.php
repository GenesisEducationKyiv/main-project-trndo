<?php

declare(strict_types=1);

namespace App\Tests\Functional\Presentation\Controller\Api;

use App\Tests\Functional\AbstractApiTestCase;
use App\Tests\Functional\Utils\CurrencyRateComparator\TestCurrencyRateComparatorChain;
use Symfony\Component\HttpFoundation\Response;

class CurrencyRateControllerTest extends AbstractApiTestCase
{
    public function testExpects200(): void
    {
        $rate = 999123.23;

        $comparator = self::getContainer()->get(TestCurrencyRateComparatorChain::class);

        $comparator->expected = $rate;

        $result = self::httpGet('/api/rate');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame($rate, json_decode($result, true));
    }
}
