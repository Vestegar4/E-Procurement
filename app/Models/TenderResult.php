<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderResult extends Model
{
    protected $fillable = [
        'tender_id',
        'winner_vendor_id',
        'winning_bid',
        'notes',
        'selected_by',
        'selected_at',
    ];

    protected $casts = [
        'selected_at' => 'datetime',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function winner()
    {
        return $this->belongsTo(Vendor::class, 'winner_vendor_id');
    }

    public function selector()
    {
        return $this->belongsTo(Admin::class, 'selected_by');
    }
}
