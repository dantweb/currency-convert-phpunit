<?php declare(strict_types=1);

use App\ConverterApp;
use App\DataFormat\DataFormatJson;
use App\Model\Currency;
use App\Service\CurrencyService;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ConvertorAppTest extends TestCase
{
    private string $srcJson;

    public function setUp(): void
    {
        $this->srcJson =
            '{
                "baseCurrency": "EUR",
                "exchangeRates" : {
                    "EUR": 1,
                    "USD": 5,
                    "CHF": 0.97,
                    "CNY": 2.3
                }
            }'
        ;
    }

    public function testConvertorApp(): void
    {
        $converterApp = new ConverterApp($this->srcJson, new DataFormatJson(), new DataFormatJson());

        $assertedCurrencyEur = new Currency(isoCode: 'EUR', rate: 1);

        $result = $converterApp->convert(1, $assertedCurrencyEur);
        $expectedResultValue1 = '[{"EUR":1},{"USD":5},{"CHF":0.97},{"CNY":2.3}]';
        $this->assertEquals($expectedResultValue1, $result);

        $result = $converterApp->convert(10, $assertedCurrencyEur);
        $expectedResultValue10 = '[{"EUR":10},{"USD":50},{"CHF":9.7},{"CNY":23}]';
        $this->assertEquals($expectedResultValue10, $result);

        $result = $converterApp->convert(0.5, $assertedCurrencyEur);
        $expectedResultValue05 = '[{"EUR":0.5},{"USD":2.5},{"CHF":0.485},{"CNY":1.15}]';
        $this->assertEquals($expectedResultValue05, $result);

        $assertedCurrencyUsd = new Currency(isoCode: 'USD', rate: 5);
        $result = $converterApp->convert(5, $assertedCurrencyUsd);
        $expectedResultValueUsd1 = '[{"EUR":1},{"USD":5},{"CHF":0.97},{"CNY":2.3}]';
        $this->assertEquals($expectedResultValueUsd1, $result);
    }

    public function testConvertorAppDataError(): void
    {
        $converterApp = new ConverterApp($this->srcJson, new DataFormatJson(), new DataFormatJson());

        $assertedCurrencyEur = new Currency(isoCode: 'EUR', rate: 1);

        $this->expectException(TypeError::class);
        $result = $converterApp->convert("1", $assertedCurrencyEur);
    }

    /**
     * @throws ReflectionException
     */
    public function testLoadCurrencies(): void
    {
        $converterApp = new ConverterApp($this->srcJson, new DataFormatJson(), new DataFormatJson());

        $loadCurrenciesArg = [
                'baseCurrency' => 'EUR',
                'exchangeRates' => [
                    "EUR" => 1,
                    "USD" => 5,
                    "CHF" => 0.97,
                    "CNY" => 2.3,
                    "THB" => 31,
                ]
        ];


        $currencyCollection = self::callPrivateMethod($converterApp, 'loadCurrencies');

        /** @var ArrayCollection $arrayCollection */
        $arrayCollection = $currencyCollection->invokeArgs($converterApp,  [ $loadCurrenciesArg ]);

        $this->assertEquals(5, $arrayCollection->count());
        $this->assertEquals('EUR', $arrayCollection->first()->getIsoCode());

        $service = new CurrencyService($arrayCollection);
        $priceMatrixArr = $service->calculatePriceMatrix(1);

        $this->assertCount(5, $priceMatrixArr);
        $this->assertEquals(31, $priceMatrixArr[4]['THB']);
    }

    /**
     * @throws ReflectionException
     */
    public static function callPrivateMethod(object $object, string $methodName): ReflectionMethod
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}