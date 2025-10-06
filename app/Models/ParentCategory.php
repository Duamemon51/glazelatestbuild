<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ParentCategory extends Model
{
    protected $fillable = ['name', 'image', 'home_image', 'category_image'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'parent_category_id');
    }

    public function productTypes()
    {
        return $this->hasManyThrough(
            ProductType::class, 
            Subcategory::class,
            'parent_category_id',
            'subcategory_id',
            'id',
            'id'
        );
    }
}
