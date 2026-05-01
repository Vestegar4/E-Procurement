<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenders extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'budget',
        'deadline',
        'published_at',
        'started_at',
        'closed_at',
        'status',
        'created_by',
    ];
}
