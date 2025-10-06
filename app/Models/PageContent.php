<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageContent extends Model
{
    use HasFactory;

    protected $table = 'page_content';

    protected $fillable = [
        'page_id',
        'section_type',
        'section_name',
        'layout_type',
        'content',
        'column_1_content',
        'column_2_content',
        'column_3_content',
        'data',
        'settings',
        'column_settings',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'page_id' => 'integer',
        'sort_order' => 'integer',
        'data' => 'array',
        'settings' => 'array',
        'column_settings' => 'array',
    ];

    const TYPE_TEXT = 'text';
    const TYPE_HTML = 'html';
    const TYPE_IMAGE = 'image';
    const TYPE_GALLERY = 'gallery';
    const TYPE_VIDEO = 'video';
    const TYPE_EMBED = 'embed';

    const LAYOUT_SINGLE = 'single';
    const LAYOUT_TWO_COLUMN = 'two_column';
    const LAYOUT_THREE_COLUMN = 'three_column';

    /**
     * Get the page that owns this content section.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Scope for specific section type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('section_type', $type);
    }

    /**
     * Scope for specific layout type.
     */
    public function scopeWithLayout($query, $layout)
    {
        return $query->where('layout_type', $layout);
    }

    /**
     * Check if this section uses multiple columns.
     */
    public function isMultiColumn(): bool
    {
        return in_array($this->layout_type, [self::LAYOUT_TWO_COLUMN, self::LAYOUT_THREE_COLUMN]);
    }

    /**
     * Get the number of columns for this section.
     */
    public function getColumnCount(): int
    {
        return match($this->layout_type) {
            self::LAYOUT_TWO_COLUMN => 2,
            self::LAYOUT_THREE_COLUMN => 3,
            default => 1,
        };
    }

    /**
     * Get column content by column number.
     */
    public function getColumnContent(int $columnNumber): ?string
    {
        // Get the column type from data field
        $columnType = $this->data["column_{$columnNumber}_type"] ?? 'content';
        
        switch ($columnType) {
            case 'content':
                // Return rich text content
                return match($columnNumber) {
                    1 => $this->column_1_content,
                    2 => $this->column_2_content,
                    3 => $this->column_3_content,
                    default => null,
                };
                
            case 'image':
                // Handle image content
                $imageUrl = $this->data["column_{$columnNumber}_image_url"] ?? null;
                $imagePath = $this->data["column_{$columnNumber}_image_path"] ?? null;
                
                if ($imagePath) {
                    $src = asset('storage/' . $imagePath);
                } elseif ($imageUrl) {
                    $src = $imageUrl;
                } else {
                    return null;
                }
                
                return '<div class="text-center"><img src="' . $src . '" alt="Column image" class="img-fluid rounded shadow"></div>';
                
            case 'gallery':
                // Handle gallery content
                $galleryUrls = $this->data["column_{$columnNumber}_gallery_urls"] ?? null;
                $galleryPaths = $this->data["column_{$columnNumber}_gallery_paths"] ?? [];
                
                $images = [];
                
                // Add uploaded images
                if (is_array($galleryPaths)) {
                    foreach ($galleryPaths as $path) {
                        $images[] = asset('storage/' . $path);
                    }
                }
                
                // Add URL images
                if ($galleryUrls) {
                    $urls = explode("\n", $galleryUrls);
                    foreach ($urls as $url) {
                        $url = trim($url);
                        if ($url) {
                            $images[] = $url;
                        }
                    }
                }
                
                if (empty($images)) {
                    return null;
                }
                
                $html = '<div class="row g-2">';
                foreach ($images as $image) {
                    $html .= '<div class="col-6"><img src="' . $image . '" alt="Gallery image" class="img-fluid rounded shadow"></div>';
                }
                $html .= '</div>';
                
                return $html;
                
            case 'video':
                // Handle video content
                $videoUrl = $this->data["column_{$columnNumber}_video"] ?? null;
                
                if (!$videoUrl) {
                    return null;
                }
                
                $embedCode = null;
                
                // Convert YouTube URLs to embed format
                if (strpos($videoUrl, 'youtube.com/watch') !== false) {
                    preg_match('/[?&]v=([^&]+)/', $videoUrl, $matches);
                    if (isset($matches[1])) {
                        $embedCode = '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' . $matches[1] . '" frameborder="0" allowfullscreen></iframe>';
                    }
                }
                // Convert YouTube short URLs
                elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                    preg_match('/youtu\.be\/([^?]+)/', $videoUrl, $matches);
                    if (isset($matches[1])) {
                        $embedCode = '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' . $matches[1] . '" frameborder="0" allowfullscreen></iframe>';
                    }
                }
                // Convert Vimeo URLs
                elseif (strpos($videoUrl, 'vimeo.com/') !== false) {
                    preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches);
                    if (isset($matches[1])) {
                        $embedCode = '<iframe width="100%" height="200" src="https://player.vimeo.com/video/' . $matches[1] . '" frameborder="0" allowfullscreen></iframe>';
                    }
                }
                
                return $embedCode ? '<div class="text-center">' . $embedCode . '</div>' : null;
                
            default:
                return match($columnNumber) {
                    1 => $this->column_1_content,
                    2 => $this->column_2_content,
                    3 => $this->column_3_content,
                    default => null,
                };
        }
    }

    /**
     * Get all column contents as an array.
     */
    public function getAllColumnContents(): array
    {
        $columns = [];
        for ($i = 1; $i <= $this->getColumnCount(); $i++) {
            $content = $this->getColumnContent($i);
            if ($content !== null) {
                $columns[] = $content;
            }
        }
        return $columns;
    }

    /**
     * Check if content is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->content) && empty($this->title);
    }
}