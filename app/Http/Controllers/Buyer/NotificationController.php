<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function open($id)
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->firstOrFail();

        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('buyer.enquiries.pending'));
    }
}
