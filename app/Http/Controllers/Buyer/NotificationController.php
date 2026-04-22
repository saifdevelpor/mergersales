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
        $data = (array) $notification->data;

        // Preferred: route-based redirect (host/environment safe)
        if (!empty($data['route_name'])) {
            $params = is_array($data['route_params'] ?? null) ? $data['route_params'] : [];
            return redirect()->route($data['route_name'], $params);
        }

        // Backward compatibility for old notifications with absolute URLs
        if (!empty($data['url'])) {
            $url = (string) $data['url'];
            $path = parse_url($url, PHP_URL_PATH);
            if (!empty($path)) {
                return redirect($path);
            }
            return redirect($url);
        }

        return redirect()->route('buyer.enquiries.pending');
    }
}
