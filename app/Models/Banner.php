<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'image_banner',
        'description',
        'status',
        'order_number'
    ];

    protected $casts = [
        'status' => 'integer',
        'order_number' => 'integer',
    ];
}
