<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'vendors';

    protected $fillable = [
        'user_id',
        'company_name',
        'address',
        'phone',
        'npwp',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function participants()
    {
        return $this->hasMany(TenderParticipant::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winningResults()
    {
        return $this->hasMany(TenderResult::class, 'winner_vendor_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
