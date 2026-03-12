<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'image',
        'description',
        'status',
        'parent_id',
        'order_number',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /* Quan hệ với danh mục cha (chính bảng Category) */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    /* Quan hệ với các danh mục con (chính bảng Category) */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    /* Quan hệ với model Product */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
