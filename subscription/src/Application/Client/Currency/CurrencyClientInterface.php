<?php

declare(strict_types=1);

namespace App\Application\Client\Currency;

interface CurrencyClientInterface
{
    public function getRate(): float;
}
