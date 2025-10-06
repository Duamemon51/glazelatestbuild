<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminColor extends Model
{
    use HasFactory;

    protected $table = 'colors';

    protected $fillable = [
        'color_category_id',
        'name',
        'code',
        'rgb_r',
        'rgb_g',
        'rgb_b',
        'closest_association',
        'description',
        'coloring_system',
    ];

    protected $casts = [
        'rgb_r' => 'integer',
        'rgb_g' => 'integer',
        'rgb_b' => 'integer',
    ];

    /**
     * Get the category that owns the color.
     */
    public function category()
    {
        return $this->belongsTo(ColorCategory::class, 'color_category_id');
    }

    /**
     * Get the RGB hex code.
     */
    public function getHexCodeAttribute()
    {
        return sprintf('#%02x%02x%02x', $this->rgb_r, $this->rgb_g, $this->rgb_b);
    }

    /**
     * Get the RGB CSS style.
     */
    public function getRgbStyleAttribute()
    {
        return "rgb({$this->rgb_r}, {$this->rgb_g}, {$this->rgb_b})";
    }

    /**
     * Get formatted coloring system name.
     */
    public function getColoringSystemNameAttribute()
    {
        return match($this->coloring_system) {
            'pantone_coated' => 'Pantone Coated',
            'pantone_uncoated' => 'Pantone Uncoated',
            'hks_k' => 'HKS K',
            'hks_n' => 'HKS N',
            'ral' => 'RAL',
            default => $this->coloring_system,
        };
    }
}
