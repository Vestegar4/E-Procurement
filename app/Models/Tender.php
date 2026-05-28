<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tender extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenders';

    protected $fillable = [
        'title',
        'description',
        'specification',
        'budget',
        'document_path',
        'status',
        'created_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function timeline()
    {
        return $this->hasOne(TenderTimeline::class);
    }

    public function participants()
    {
        return $this->hasMany(TenderParticipant::class);
    }

    public function announcements()
    {
        return $this->hasMany(TenderAnnouncement::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function result()
    {
        return $this->hasOne(TenderResult::class);
    }

    public function aanwijzings()
    {
        // Satu tender bisa memiliki banyak pertanyaan (hasMany)
        return $this->hasMany(Aanwijzing::class);
    }
    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */
    public function isBiddingOpen()
    {
        if (!$this->timeline) {
            return false;
        }

        return now()->between(
            $this->timeline->bidding_start,
            $this->timeline->bidding_end
        );
    }
}
