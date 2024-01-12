<?php

use App\Helpers\CRM\General\NumberFormatterHelper;

if (!function_exists('number_with_currency_symbol')) {
    function number_with_currency_symbol($number)
    {
        return resolve(NumberFormatterHelper::class)->numberWithCurrencySymbol($number);
    }
}
