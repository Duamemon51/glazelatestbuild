<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pattern extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'svg_content',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the product that owns the pattern.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'pattern_product');
    }

    /**
     * Scope a query to only include active patterns.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}