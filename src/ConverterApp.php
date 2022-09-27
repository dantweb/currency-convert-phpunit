<?php declare(strict_types=1);

namespace App;

use App\DataFormat\DataFormatInterface;
use App\DataFormat\DataFormatJson;
use App\Model\Currency;
use App\Service\CurrencyConverter;
use App\Service\CurrencyService;
use Doctrine\Common\Collections\ArrayCollection;

class ConverterApp
{
    public const CSV = 'csv';
    public const JSON = 'json';

    private CurrencyConverter $currencyConverter;
    private ArrayCollection $currencyCollection;

    public function __construct(
        private string $rawTextData,
        private DataFormatInterface $inputDataFormat,
        private DataFormatInterface $outputDataFormat)
    {
        $inputDataArray = $this->inputDataFormat->importRawData($this->rawTextData);
        $this->currencyCollection = $this->loadCurrencies($inputDataArray);

        $this->currencyConverter = new CurrencyConverter(
            new CurrencyService($this->currencyCollection),
            $this->outputDataFormat
        );
    }

    public function convert(float $amount, Currency $currency): string
    {
        return $this->currencyConverter->convert($amount, $currency);
    }

    private function loadCurrencies(array $inputData): ArrayCollection
    {
        $currencyCollection = new ArrayCollection();
        foreach($inputData['exchangeRates'] as $iso => $rate)
        {
            $currencyCollection->add(new Currency(isoCode: $iso, rate: $rate));
        }

        return $currencyCollection;
    }

    public function getInputDataFormat(): DataFormatInterface
    {
        return $this->inputDataFormat;
    }

    public function setInputDataFormat(DataFormatInterface $inputDataFormat): void
    {
        $this->inputDataFormat = $inputDataFormat;
    }

    public function getCurrencyCollection(): ArrayCollection
    {
        return $this->currencyCollection;
    }
}