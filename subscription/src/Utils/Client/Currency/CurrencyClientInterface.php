<?php

declare(strict_types=1);

namespace App\Utils\Client\Currency;

interface CurrencyClientInterface
{
    public function getRate(): float;
}
