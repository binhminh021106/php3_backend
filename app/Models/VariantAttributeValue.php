<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttributeValue extends Model
{
    use HasFactory;

    protected $table = 'variant_attribute_values';

    protected $fillable = [
        'variant_id',
        'attribute_id',
        'value',
    ];

    /* Quan hệ với model ProductVariant */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    /* Quan hệ với model Attribute */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}