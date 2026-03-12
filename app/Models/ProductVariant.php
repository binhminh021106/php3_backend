<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku_variant',
        'price',
        'quantity',
        'image',
    ];

    protected $casts = [
        'price' => 'double',
        'quantity' => 'integer',
    ];

    /* Quan hệ với model Product (Sản phẩm chính) */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /* Quan hệ với model VariantAttributeValue (Các giá trị thuộc tính như Màu, Size) */
    public function attributeValues()
    {
        return $this->hasMany(VariantAttributeValue::class, 'variant_id', 'id');
    }
}