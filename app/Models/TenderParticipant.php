<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderParticipant extends Model
{
    protected $fillable = [
        'tender_id',
        'vendor_id',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
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
