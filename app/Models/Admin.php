<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'name',
        'role',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function tenders()
    {
        return $this->hasMany(Tender::class, 'created_by');
    }

    public function announcements()
    {
        return $this->hasMany(TenderAnnouncement::class, 'created_by');
    }

    public function selectedResults()
    {
        return $this->hasMany(TenderResult::class, 'selected_by');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
