<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the menu items for this menu.
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    /**
     * Get active menu items.
     */
    public function activeMenuItems(): HasMany
    {
        return $this->menuItems()->where('is_active', true);
    }

    /**
     * Get root menu items (no parent).
     */
    public function rootMenuItems(): HasMany
    {
        return $this->menuItems()->whereNull('parent_id');
    }

    /**
     * Scope for active menus.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific location.
     */
    public function scopeLocation($query, $location)
    {
        return $query->where('location', $location);
    }
}