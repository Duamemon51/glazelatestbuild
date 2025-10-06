<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoData extends Model
{
    use HasFactory;

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_card',
        'structured_data',
        'seo_score_data',
        'seo_score',
        'seo_issues',
        'seo_recommendations',
        'noindex',
        'nofollow',
    ];

    protected $casts = [
        'structured_data' => 'array',
        'seo_score_data' => 'array',
        'seo_score' => 'integer',
        'seo_issues' => 'array',
        'seo_recommendations' => 'array',
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
    ];

    /**
     * Get the parent seoable model.
     */
    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Calculate SEO score based on various factors.
     */
    public function calculateSeoScore(): int
    {
        $score = 0;

        // Title optimization (30 points)
        if ($this->meta_title && strlen($this->meta_title) >= 30 && strlen($this->meta_title) <= 60) {
            $score += 30;
        } elseif ($this->meta_title) {
            $score += 15; // Partial credit for having a title
        }

        // Meta description (20 points)
        if ($this->meta_description && strlen($this->meta_description) >= 120 && strlen($this->meta_description) <= 160) {
            $score += 20;
        } elseif ($this->meta_description) {
            $score += 10;
        }

        // Keywords (10 points)
        if ($this->meta_keywords) {
            $score += 10;
        }

        // Open Graph (15 points)
        $ogScore = 0;
        if ($this->og_title) $ogScore += 5;
        if ($this->og_description) $ogScore += 5;
        if ($this->og_image) $ogScore += 5;
        $score += $ogScore;

        // Twitter Cards (10 points)
        $twitterScore = 0;
        if ($this->twitter_title) $twitterScore += 3;
        if ($this->twitter_description) $twitterScore += 3;
        if ($this->twitter_image) $twitterScore += 4;
        $score += $twitterScore;

        // Structured data (10 points)
        if ($this->structured_data) {
            $score += 10;
        }

        // Canonical URL (5 points)
        if ($this->canonical_url) {
            $score += 5;
        }

        return min(100, $score);
    }

    /**
     * Analyze SEO issues and generate recommendations.
     */
    public function analyzeSeo(): array
    {
        $issues = [];
        $recommendations = [];

        // Title analysis
        if (!$this->meta_title) {
            $issues[] = 'Missing meta title';
            $recommendations[] = 'Add a compelling meta title between 30-60 characters';
        } elseif (strlen($this->meta_title) < 30) {
            $issues[] = 'Meta title too short';
            $recommendations[] = 'Expand meta title to at least 30 characters';
        } elseif (strlen($this->meta_title) > 60) {
            $issues[] = 'Meta title too long';
            $recommendations[] = 'Shorten meta title to maximum 60 characters';
        }

        // Description analysis
        if (!$this->meta_description) {
            $issues[] = 'Missing meta description';
            $recommendations[] = 'Add a meta description between 120-160 characters';
        } elseif (strlen($this->meta_description) < 120) {
            $issues[] = 'Meta description too short';
            $recommendations[] = 'Expand meta description to at least 120 characters';
        } elseif (strlen($this->meta_description) > 160) {
            $issues[] = 'Meta description too long';
            $recommendations[] = 'Shorten meta description to maximum 160 characters';
        }

        // Keywords
        if (!$this->meta_keywords) {
            $issues[] = 'Missing meta keywords';
            $recommendations[] = 'Add relevant keywords separated by commas';
        }

        // Open Graph
        if (!$this->og_title) {
            $issues[] = 'Missing Open Graph title';
            $recommendations[] = 'Add og:title for better social media sharing';
        }
        if (!$this->og_description) {
            $issues[] = 'Missing Open Graph description';
            $recommendations[] = 'Add og:description for better social media sharing';
        }
        if (!$this->og_image) {
            $issues[] = 'Missing Open Graph image';
            $recommendations[] = 'Add og:image for better social media sharing';
        }

        return [
            'issues' => $issues,
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Update SEO score and analysis data.
     */
    public function updateSeoAnalysis(): void
    {
        $analysis = $this->analyzeSeo();
        $score = $this->calculateSeoScore();

        $this->update([
            'seo_score' => $score,
            'seo_issues' => $analysis['issues'],
            'seo_recommendations' => $analysis['recommendations'],
            'seo_score_data' => [
                'calculated_at' => now(),
                'score' => $score,
                'issues_count' => count($analysis['issues']),
                'recommendations_count' => count($analysis['recommendations']),
            ],
        ]);
    }
}