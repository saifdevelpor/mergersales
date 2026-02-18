<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',       // Related business/listing
        'user_id',          // Buyer user (nullable)
        'name',             // Buyer name
        'email',            // Buyer email
        'phone',            // Optional phone
        'company',          // Optional company
        'position',         // Optional position
        'interest_type',    // buy / partner / nda
        'budget',           // Optional budget
        'timeline',         // Optional timeline
        'message',          // Message / questions
        'attachments',      // JSON array of uploaded files
        'status',           // pending / approved / rejected
        'nda_required',      // boolean flag for NDA requirement
        'nda_status',
        'nda_file_path',
        'signed_nda_file_path',
        'buyer_signature_path',
        'nda_sent_at',
        'nda_signed_at',
    ];

    /**
     * Relationship: Enquiry belongs to a Listing
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Relationship: Enquiry belongs to a User (Buyer), optional
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get attachments as array
     */
    public function getAttachmentsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Set attachments attribute as JSON
     */
    public function setAttachmentsAttribute($value)
    {
        $this->attributes['attachments'] = $value ? json_encode($value) : null;
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
