<?php declare(strict_types=1);

require_once('../vendor/autoload.php');

use App\ConverterApp;
use App\DataFormat\DataFormatCsv;
use App\DataFormat\DataFormatJson;

$input = require('cli-app-input-src.php');

$srcPrice = $input['examplePrice'];

$inputFormatClassObj = match($input['inputFormat'])
{
    ConverterApp::CSV =>  new DataFormatCsv,
    ConverterApp::JSON =>  new DataFormatJson,
};

$outputFormatClassObj = match($input['outputFormat'])
{
    ConverterApp::CSV =>  new DataFormatCsv,
    ConverterApp::JSON =>  new DataFormatJson,
};

$app = new ConverterApp(
    rawTextData: $input['exampleRawData'],
    inputDataFormat: $inputFormatClassObj,
    outputDataFormat: $outputFormatClassObj
);

$currencies = $app->getCurrencyCollection();

foreach ($currencies as $currency)
{
    echo $app->convert($srcPrice, $currency) . "\n";
}