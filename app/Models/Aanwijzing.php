<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aanwijzing extends Model
{
    protected $guarded = []; // Mengizinkan semua kolom diisi

    // Relasi balik ke Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi balik ke Tender
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
