<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Currency;
use Doctrine\Common\Collections\ArrayCollection;

class CurrencyService
{
    public function __construct(private ArrayCollection $availableCurrencies)
    {}

    public function calculatePriceMatrix(float $priceEur): array
    {
        $result = [];

        /** @var Currency $currency */
        foreach ($this->availableCurrencies as $currency)
        {
            $convertedPrice = $currency->getRate() * $priceEur;
            $result[] = [
                $currency->getIsoCode() => $convertedPrice
            ];
        }

        return $result;
    }
}