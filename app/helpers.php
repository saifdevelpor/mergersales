<?php

use App\Services\RapidFxService;

if (!function_exists('price')) {

    /**
     * Convert and format a listing price.
     *
     * @param float  $amount  Listing price stored in DB
     * @param string $from    Listing currency stored in DB (e.g. USD/PKR)
     * @param string|null $to User selected currency (optional). default: session/cookie/default
     */
    function price(float $amount, string $from, ?string $to = null): string
    {
        $from = strtoupper($from);
        $default = strtoupper(env('DEFAULT_CURRENCY', 'USD'));
        $selected = session('currency') ?? request()->cookie('currency') ?? $default;
        $to = strtoupper($to ?? $selected);

        if ($from === $to) {
            return number_format($amount, 2) . " " . $to;
        }

        /** @var RapidFxService $fx */
        $fx = app(RapidFxService::class);

        $converted = $fx->convert($amount, $from, $to);

        return number_format($converted, 2) . " " . $to;
    }
}
