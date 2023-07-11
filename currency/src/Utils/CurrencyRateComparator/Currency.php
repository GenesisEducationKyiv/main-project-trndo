<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator;

enum Currency: string
{
    case BTC = 'BTC';
    case UAH = 'UAH';

    public function getFullName(): string
    {
        return match ($this) {
            self::BTC => 'bitcoin',
            self::UAH => 'hryvnia',
        };
    }
}
