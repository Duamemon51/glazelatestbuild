<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
   protected $fillable = [
    'name',
    'front_image',
    'back_image',
    'left_image',
    'right_image',
    'preview_img', // added column
    'is_active',
     'is_unique'
];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'design_product');
    }
}
