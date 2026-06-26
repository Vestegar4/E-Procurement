<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorDocument extends Model
{
    use HasFactory;

    protected $table = 'vendor_documents';

    protected $fillable = [
        'vendor_id',
        'document_type',
        'document_name',
        'file_path',
        'status',
        'notes',
        'uploaded_at',
        'verified_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];
    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        if (!$this->file_path) return null;

        // Jika Anda menggunakan Symlink 'api_docs' di dalam folder public Proculus:
        return asset('storage/api_docs/' . str_replace('vendor-documents/', '', $this->file_path));
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
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
