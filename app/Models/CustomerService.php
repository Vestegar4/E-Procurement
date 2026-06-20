<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    protected $guarded = [];

    // Relasi untuk menarik data perusahaan dan email
    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->with('user'); 
    }
}