<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = ['name', 'subcategory_id', 'image'];

    // ProductType belongs to a Subcategory
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    // Access ParentCategory through Subcategory
    public function parentCategory()
    {
        return $this->hasOneThrough(
            ParentCategory::class,
            Subcategory::class,
            'id',               // Subcategory table's primary key
            'id',               // ParentCategory table's primary key
            'subcategory_id',   // ProductType's foreign key to Subcategory
            'parent_category_id'// Subcategory's foreign key to ParentCategory
        );
    }

    // Products in this ProductType
    public function products()
    {
        return $this->hasMany(Product::class, 'product_type_id');
    }
}
