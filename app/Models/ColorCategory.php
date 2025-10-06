<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the colors for the category.
     */
    public function colors()
    {
        return $this->hasMany(AdminColor::class, 'color_category_id');
    }
}
