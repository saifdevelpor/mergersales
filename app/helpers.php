<?php

use App\Services\RapidFxService;
use Illuminate\Support\Facades\Crypt;

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

if (!function_exists('e_id')) {
    /**
     * Encrypt an integer ID into URL-safe token.
     */
    function e_id($id): ?string
    {
        if ($id === null || $id === '') {
            return null;
        }

        if (!is_numeric($id)) {
            return (string) $id;
        }

        $encrypted = Crypt::encryptString((string) $id);
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }
}

if (!function_exists('d_id')) {
    /**
     * Decrypt URL-safe token back to integer ID.
     */
    function d_id($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        try {
            $normalized = strtr((string) $value, '-_', '+/');
            $padding = strlen($normalized) % 4;
            if ($padding > 0) {
                $normalized .= str_repeat('=', 4 - $padding);
            }

            $decoded = base64_decode($normalized, true);
            if ($decoded === false) {
                $decoded = (string) $value;
            }

            $decrypted = Crypt::decryptString($decoded);
            return is_numeric($decrypted) ? (int) $decrypted : null;
        } catch (\Throwable $e) {
            try {
                $direct = Crypt::decryptString((string) $value);
                return is_numeric($direct) ? (int) $direct : null;
            } catch (\Throwable $e2) {
                return null;
            }
        }
    }
}
