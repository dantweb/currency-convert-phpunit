<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Currency;

interface CurrencyConverterInterface
{
    public function convert(float $amount, Currency $currency): string;
}