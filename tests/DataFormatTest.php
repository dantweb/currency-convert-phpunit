<?php

namespace App;

use App\DataFormat\DataFormatJson;
use App\Exception\WrongDataException;

class DataFormatTest extends \PHPUnit\Framework\TestCase
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

    public function testDataFormatJson(): void
    {
        $dataFormatMock = new DataFormatJson();
        $result = $dataFormatMock->importRawData($this->srcJson);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('exchangeRates', $result);
        $this->assertArrayHasKey('EUR', $result['exchangeRates']);
    }

    public function testDataFormatJsonSyntaxException(): void
    {
        $dataFormatMock = new DataFormatJson();

        $this->expectException(\JsonException::class);
        $result = $dataFormatMock->importRawData(
            '{
                "baseCurrency": "EUR",
                "exchangeRates" : {
                    "EUR" 1,
                    "USD" 5,
                    "CHF" 0.97,
                    "CNY" 2.3
                }
            }'
        );
    }

    public function testDataFormatJsonEmptyException(): void
    {
        $dataFormatMock = new DataFormatJson();

        $this->expectException(\JsonException::class);
        $result = $dataFormatMock->importRawData('');
    }

    /**
     * @throws \JsonException
     */
    public function testDataFormatJsonWrongFormatException(): void
    {
        $dataFormatMock = new DataFormatJson();

        $this->expectException(WrongDataException::class);
        $result = $dataFormatMock->importRawData('{
                "baseCurrency": "EUR",
                "exchangeRates" : {
                    "EUR": 1,
                    "USD": 5,
                    "CHF": 0.97,
                    "CNY": "a"
                }
            }');
    }
}