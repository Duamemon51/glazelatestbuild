<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'title',
        'url',
        'page_id',
        'description',
        'icon_class',
        'parent_id',
        'sort_order',
        'is_active',
        'target',
        'additional_data',
    ];

    protected $casts = [
        'page_id' => 'integer',
        'parent_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'additional_data' => 'array',
    ];

    /**
     * Get the menu that owns this menu item.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the page associated with this menu item.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get the child menu items.
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get active child menu items.
     */
    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Scope for active menu items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root menu items (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the full URL for this menu item.
     */
    public function getFullUrlAttribute(): string
    {
        if ($this->url) {
            return $this->url;
        }

        if ($this->page) {
            return route('page.show', $this->page->slug);
        }

        return '#';
    }
}