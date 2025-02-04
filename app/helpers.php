<?php


use Illuminate\Support\Facades\Cache;
if(!function_exists('currency_formatter')) {
    function currency_formatter($digit) {
        // Cache the currency symbol
        $currencySymbol = Cache::rememberForever('currency_symbol', function () {
            // Assuming App model is in the App\Models namespace
            return \App\Models\App::where('is_active',true)->value('currency_symbol');
        });
        return $currencySymbol . " " . number_format($digit, 2, '.', ',');
    }
}
