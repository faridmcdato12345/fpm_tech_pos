<?php

namespace App\Http\Services;

use NumberFormatter;

class FormatNumber {

    public function toCurrency(
        $value,
        $locale = 'en_PH',
        $style = NumberFormatter::CURRENCY,
        $precision = 2,
        $groupingUsed = true,
        $currencyCode = 'PHP'
    )
    {
        $formatter = new NumberFormatter($locale, $style);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $precision);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, $groupingUsed);
        $formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $currencyCode);
        return $formatter->format($value);
    }

}