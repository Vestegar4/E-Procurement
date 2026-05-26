<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = [
        'tender_id',
        'vendor_id',
        'bid_amount',
        'bid_document',
        'notes',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
