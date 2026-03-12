<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'sku',
        'thumbnail_image',
        'short_description',
        'description',
        'category_id',
        'brand_id',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /* Quan hệ với model Category */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /* Quan hệ với model Brand */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /* Quan hệ với model ProductVariant */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
}
