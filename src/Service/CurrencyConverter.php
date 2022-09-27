<?php declare(strict_types=1);

namespace App\Service;

use App\DataFormat\DataFormatInterface;
use App\Model\Currency;
use App\Service\CurrencyService;

class CurrencyConverter implements CurrencyConverterInterface
{
    public function __construct(private CurrencyService $currencyService, private DataFormatInterface $outputFormat)
    {}

    public function convert(float $amount, Currency $currency): string
    {
        $basePriceEur = $amount / $currency->getRate();
        $result = $this->currencyService->calculatePriceMatrix($basePriceEur);
        return $this->outputFormat->convertArray($result);
    }
}