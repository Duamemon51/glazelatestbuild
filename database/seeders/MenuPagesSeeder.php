<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class MenuPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            // Support pages
            ['title' => 'Informational Brochure', 'slug' => 'informational-brochure', 'content' => 'Brochure, color print-out, sample materials.'],
            ['title' => 'Samples Program', 'slug' => 'samples-program', 'content' => 'View and try on our products.'],
            ['title' => 'Receive a quote', 'slug' => 'receive-quote', 'content' => 'Individually calculated quotes for your needs.'],
            ['title' => 'Download Service', 'slug' => 'download-service', 'content' => 'User-friendly templates for custom designs.'],
            ['title' => 'owayo Design Service', 'slug' => 'owayo-design-service', 'content' => 'We make your ideas into design.'],
            ['title' => 'Club and School Rewards Program', 'slug' => 'club-school-rewards', 'content' => 'Special program for registered clubs.'],
            ['title' => 'How do I order?', 'slug' => 'how-do-i-order', 'content' => 'The essentials on ordering your gear.'],
            ['title' => 'Size Chart', 'slug' => 'size-chart', 'content' => 'Find the right size for your needs.'],
            ['title' => 'Production Time', 'slug' => 'production-time', 'content' => 'Fast and precise delivery times.'],
            ['title' => 'Price List', 'slug' => 'price-list', 'content' => 'Unit prices for all products.'],
            ['title' => 'Your owayo Shop', 'slug' => 'your-owayo-shop', 'content' => 'Save time & earn money with your own shop.'],
            
            // About ricona pages
            ['title' => 'News', 'slug' => 'news', 'content' => 'What\'s happening at ricona.'],
            ['title' => 'Tried and tested reviews', 'slug' => 'tried-and-tested-reviews', 'content' => 'Here\'s what our customers say.'],
            ['title' => 'References', 'slug' => 'references', 'content' => 'A glimpse of our customer base.'],
            ['title' => 'Design of the Day', 'slug' => 'design-of-the-day', 'content' => 'Design examples and inspiration.'],
            ['title' => 'Athletes and Teams', 'slug' => 'athletes-and-teams', 'content' => 'The ricona family.'],
            ['title' => 'Environmental Responsibility', 'slug' => 'environmental-responsibility', 'content' => 'We don\'t just preach environmentalism.'],
            ['title' => 'Inside', 'slug' => 'inside', 'content' => 'The ricona story.'],
            
            // Contact pages
            ['title' => 'Contact Form', 'slug' => 'contact-form', 'content' => 'Have a question? We\'re here to help.'],
            ['title' => 'owayo Newsletter', 'slug' => 'owayo-newsletter', 'content' => 'Subscribe to the ricona newsletter.']
        ];

        foreach ($pages as $pageData) {
            $existing = Page::where('slug', $pageData['slug'])->first();
            if (!$existing) {
                $page = Page::create([
                    'title' => $pageData['title'],
                    'slug' => $pageData['slug'],
                    'status' => 'published',
                    'visibility' => 'public',
                    'author_id' => 1,
                    'featured_image' => 'images/hero.avif'
                ]);
                
                $page->contentSections()->create([
                    'section_type' => 'text',
                    'content' => $pageData['content'],
                    'sort_order' => 1,
                    'is_active' => true
                ]);
                
                $this->command->info('Created page: ' . $page->title);
            } else {
                $this->command->info('Page already exists: ' . $pageData['title']);
            }
        }
    }
}
