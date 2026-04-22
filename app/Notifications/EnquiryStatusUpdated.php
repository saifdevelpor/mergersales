<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class EnquiryStatusUpdated extends Notification
{
    public $enquiry;
    public string $type;

    public function __construct($enquiry, string $type = 'status_update')
    {
        $this->enquiry = $enquiry;
        $this->type = $type; // 'status_update' | 'nda_sent'
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $status = $this->enquiry->status;

        // Buyer pages
        $buyerRouteName = match ($status) {
            'approved' => 'buyer.enquiries.approved',
            'rejected' => 'buyer.enquiries.rejected',
            default    => 'buyer.enquiries.pending',
        };
        $buyerUrl = route($buyerRouteName, [], false);

        // ✅ Seller listing enquiries page (BEST)
        // apne route name ke mutabiq change karna:
        $sellerRouteName = 'seller.listing.enquiries';
        $sellerRouteParams = ['listing' => e_id($this->enquiry->listing_id)];
        $sellerUrl = route($sellerRouteName, $sellerRouteParams, false);

        if ($this->type === 'new_enquiry') {
            $message = 'New enquiry received on your listing.';
            $url = $sellerUrl;
            $routeName = $sellerRouteName;
            $routeParams = $sellerRouteParams;
        } elseif ($this->type === 'nda_sent') {
            $message = 'NDA has been sent for the enquiry.';
            $url = $buyerUrl;
            $routeName = $buyerRouteName;
            $routeParams = [];
        } elseif ($this->type === 'nda_signed') {
            $message = 'NDA completed: Buyer signed and submitted the NDA.';
            $url = $sellerUrl;
            $routeName = $sellerRouteName;
            $routeParams = $sellerRouteParams;
        } else {
            $message = 'Your enquiry status changed to ' . ucfirst($status);
            $url = $buyerUrl;
            $routeName = $buyerRouteName;
            $routeParams = [];
        }

        return [
            'type'       => $this->type,
            'message'    => $message,
            'status'     => $status,
            'enquiry_id' => $this->enquiry->id,
            'url'        => $url,
            'route_name' => $routeName,
            'route_params' => $routeParams,
        ];
    }
}
