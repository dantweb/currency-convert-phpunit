<?php declare(strict_types=1);

namespace App\DataFormat;

use App\Exception\WrongDataException;
use JsonException;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class DataFormatJson implements DataFormatInterface
{
    public function convertArray(array $array): string
    {
        return json_encode($array, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     * @throws WrongDataException
     */
    public function importRawData(string $rawTextData): array
    {
        $result = json_decode($rawTextData, true, 512, JSON_THROW_ON_ERROR);
        if (isset($result['exchangeRates']) && is_array($result['exchangeRates']))
        {
            foreach($result['exchangeRates'] as $key => &$value)
            {
                if (!is_float($value) && !is_int($value))
                {
                    throw new WrongDataException("$key => $value");
                }
                else
                {
                    $value = (float)$value;
                }
            }
        }

        return $result;
    }
}