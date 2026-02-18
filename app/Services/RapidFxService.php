<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RapidFxService
{
    public function rate(string $from, string $to): float
    {
        $from = strtoupper($from);
        $to   = strtoupper($to);

        if ($from === $to) return 1.0;

        $cacheKey = "fx_rate_{$from}_{$to}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($from, $to) {

            $res = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders([
                    'X-RapidAPI-Key'  => env('RAPIDAPI_KEY'),
                    'X-RapidAPI-Host' => env('RAPIDAPI_HOST'),
                ])
                ->get(rtrim(env('RAPIDAPI_BASE_URL'), '/') . '/convert', [
                    'from'   => $from,
                    'to'     => $to,
                    'amount' => 1, // ✅ 1 amount se rate nikalta hai
                ]);

            if (!$res->ok()) {
                return 1.0;
            }

            $data = $res->json();

            // ✅ Your API response format:
            // info.rate = rate
            $rate = $data['info']['rate'] ?? null;

            return $rate ? (float) $rate : 1.0;
        });
    }

    public function convert(float $amount, string $from, string $to): float
    {
        $rate = $this->rate($from, $to);
        return $amount * $rate;
    }
}
