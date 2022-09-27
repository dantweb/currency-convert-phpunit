<?php

/***********************
 * random initial data
 ***********************/
return [
            'exampleRawData' => '{
                            "baseCurrency": "EUR",
                            "exchangeRates" : {
                                "EUR": 1,
                                "USD": 5,
                                "CHF": 0.97,
                                "CNY": 2.3
                            }}',
            'inputFormat' => \App\ConverterApp::JSON,
            'outputFormat' => App\ConverterApp::JSON,
            'examplePrice' => 1
    ];
