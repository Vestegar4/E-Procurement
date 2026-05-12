<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderAnnouncement extends Model
{
    protected $fillable = [
        'tender_id',
        'title',
        'message',
        'created_by',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
