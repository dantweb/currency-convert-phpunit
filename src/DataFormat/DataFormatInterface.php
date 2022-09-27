<?php declare(strict_types=1);

namespace App\DataFormat;

interface DataFormatInterface
{
    public function convertArray(array $array): string;

    public function importRawData(string $rawTextData): array;
}