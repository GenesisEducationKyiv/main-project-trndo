<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorChainInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CurrencyRateController
{
    public function __construct(
        private CurrencyRateComparatorChainInterface $rateComparator
    ) {
    }

    #[Route('/rate')]
    public function getRates(): JsonResponse
    {
        return new JsonResponse($this->rateComparator->execute(Currency::BTC, Currency::UAH));
    }
}
