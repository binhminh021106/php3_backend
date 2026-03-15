<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'image',
        'description',
        'status',
        'order_number'
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /* Quan hệ với model Product (1 Thương hiệu có nhiều Sản phẩm) */
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
}