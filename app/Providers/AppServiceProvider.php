<?php

namespace App\Providers;

use App\Models\ChMessage;
use App\Models\ChatNotification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ChMessage::created(function (ChMessage $message): void {
            // Ignore self messages (e.g. saved messages)
            if ((int) $message->from_id === (int) $message->to_id) {
                return;
            }

            $listingId = $message->listing_id ?: request('listing_id');

            ChatNotification::create([
                'from_id' => (int) $message->from_id,
                'to_id' => (int) $message->to_id,
                'listing_id' => $listingId ? (int) $listingId : null,
                // ch_messages.id is UUID in this project; keep numeric fallback for legacy schema.
                'message_id' => is_numeric($message->id) ? (int) $message->id : 0,
                'seen' => 0,
            ]);
        });
    }
}
