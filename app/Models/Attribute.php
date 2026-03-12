<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';

    protected $fillable = [
        'name',
        'display_name',
    ];

    /* Quan hệ với model VariantAttributeValue */
    public function variantValues()
    {
        return $this->hasMany(VariantAttributeValue::class, 'attribute_id', 'id');
    }
}