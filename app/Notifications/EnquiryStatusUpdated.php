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
        $buyerUrl = match ($status) {
            'approved' => route('buyer.enquiries.approved'),
            'rejected' => route('buyer.enquiries.rejected'),
            default    => route('buyer.enquiries.pending'),
        };

        // ✅ Seller listing enquiries page (BEST)
        // apne route name ke mutabiq change karna:
        $sellerUrl = route('seller.listing.enquiries', $this->enquiry->listing_id);

        if ($this->type === 'new_enquiry') {
            $message = 'New enquiry received on your listing.';
            $url = $sellerUrl;
        } elseif ($this->type === 'nda_sent') {
            $message = 'NDA has been sent for the enquiry.';
            $url = $buyerUrl;
        } elseif ($this->type === 'nda_signed') {
            $message = 'NDA completed: Buyer signed and submitted the NDA.';
            $url = $sellerUrl;
        } else {
            $message = 'Your enquiry status changed to ' . ucfirst($status);
            $url = $buyerUrl;
        }

        return [
            'type'       => $this->type,
            'message'    => $message,
            'status'     => $status,
            'enquiry_id' => $this->enquiry->id,
            'url'        => $url,
        ];
    }
}
