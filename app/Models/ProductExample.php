<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductExample extends Model
{
    protected $fillable = ['name', 'product_type_id', 'image', 'model_3d'];

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}
