<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'product_type_id',
        'type',
        'gender',
        'price',
        'quantity',
        'prices',
        'image',
        'model_3d',
        'model_3d_url',
        'is_active',
        'details', // <-- added this line
    ];

    protected $casts = [
        'prices' => 'array',
    ];


    /*** Product belongs to a ProductType ***/
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    /*** Access Subcategory via ProductType (2 levels) ***/
    public function subcategory()
    {
        return $this->hasOneThrough(
            Subcategory::class,
            ProductType::class,
            'id',               // ProductType primary key
            'id',               // Subcategory primary key
            'product_type_id',  // Product foreign key
            'subcategory_id'    // ProductType foreign key
        );
    }

    /*** Access ParentCategory via ProductType → Subcategory → ParentCategory (3 levels) ***/
    public function parentCategory()
    {
        // 3-level ke liye nested relation use karenge
        return $this->productType()
                    ->with('subcategory.parentCategory')
                    ->get()
                    ->pluck('subcategory.parentCategory')
                    ->first();
    }
public function getParentCategoryAttribute()
{
    return $this->productType?->subcategory?->parentCategory;
}

    /*** Scope: only active products ***/
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function designs()
{
    return $this->belongsToMany(Design::class, 'design_product', 'product_id', 'design_id');
}

    /*** Product has many patterns ***/
    public function patterns()
    {
        return $this->belongsToMany(Pattern::class, 'pattern_product', 'product_id', 'pattern_id');
    }

    /**
     * Get the price for a given quantity based on tiered pricing
     * If no tiered pricing is set, returns the base price
     */
    public function getPriceForQuantity($quantity)
    {
        // If tiered pricing is set, find the appropriate price tier
        if ($this->prices && is_array($this->prices)) {
            // Sort tiers by min_quantity ascending
            $tiers = collect($this->prices)->sortBy('min_quantity');

            foreach ($tiers as $tier) {
                if ($quantity >= $tier['min_quantity']) {
                    return $tier['price'];
                }
            }
        }

        // Fallback to base price if no tier matches or no tiers set
        return $this->price;
    }

    /**
     * Get all pricing tiers formatted for display
     */
    public function getPricingTiers()
    {
        if (!$this->prices || !is_array($this->prices)) {
            return [['min_quantity' => 1, 'price' => $this->price ?? 0]];
        }

        return collect($this->prices)->sortBy('min_quantity')->values()->all();
    }

}
