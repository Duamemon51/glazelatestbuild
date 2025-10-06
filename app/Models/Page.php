<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'status',
        'visibility',
        'published_at',
        'author_id',
        'template',
        'featured_image',
        'settings',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'author_id' => 'integer',
        'settings' => 'array',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ARCHIVED = 'archived';

    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PRIVATE = 'private';
    const VISIBILITY_PASSWORD = 'password';

    /**
     * Get the page content sections.
     */
    public function contentSections(): HasMany
    {
        return $this->hasMany(PageContent::class)->orderBy('sort_order');
    }

    /**
     * Get the SEO data for this page.
     */
    public function seoData(): MorphOne
    {
        return $this->morphOne(SeoData::class, 'seoable');
    }

    /**
     * Get the author of this page.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get menu items that link to this page.
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * Scope for published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                    ->where('visibility', self::VISIBILITY_PUBLIC)
                    ->where(function ($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    });
    }

    /**
     * Scope for public visibility.
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', self::VISIBILITY_PUBLIC);
    }

    /**
     * Check if page is published.
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED &&
               $this->visibility === self::VISIBILITY_PUBLIC &&
               ($this->published_at === null || $this->published_at->isPast());
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}