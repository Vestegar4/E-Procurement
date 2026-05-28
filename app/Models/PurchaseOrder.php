<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'po_number',
        'total_amount',
        'status',
        'notes'
    ];

    // Relasi ke Tender (Jika belum ada, sekalian ditambahkan)
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    // Relasi ke Vendor (Jika belum ada, sekalian ditambahkan)
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
