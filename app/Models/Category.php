<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id', 'product_type_id'];

    // Link to ParentCategory model
    public function parent()
    {
        return $this->belongsTo(ParentCategory::class, 'parent_id');
    }

    // Agar future me nested categories karni ho, to yeh rahe
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}
