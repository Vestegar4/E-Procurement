<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderTimeline extends Model
{
    protected $fillable = [
        'tender_id',
        'registration_start',
        'registration_end',
        'aanwijzing_at',
        'bidding_start',
        'bidding_end',
    ];

    protected $casts = [
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'aanwijzing_at' => 'datetime',
        'bidding_start' => 'datetime',
        'bidding_end' => 'datetime',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
