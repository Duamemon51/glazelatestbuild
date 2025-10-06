<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main navigation menu
        $headerMenu = Menu::firstOrCreate([
            'slug' => 'header-menu'
        ], [
            'name' => 'Header Navigation',
            'description' => 'Main navigation menu for the header',
            'location' => 'header',
            'is_active' => true,
            'sort_order' => 1
        ]);

        // Create Support menu
        $supportMenuItem = MenuItem::firstOrCreate([
            'menu_id' => $headerMenu->id,
            'title' => 'Support'
        ], [
            'url' => null,
            'page_id' => null,
            'description' => 'Customer support and services',
            'icon_class' => 'bi bi-question-circle',
            'parent_id' => null,
            'sort_order' => 1,
            'is_active' => true,
            'target' => '_self'
        ]);

        // Support submenu items
        $supportPages = [
            ['title' => 'Informational Brochure', 'slug' => 'informational-brochure'],
            ['title' => 'Samples Program', 'slug' => 'samples-program'],
            ['title' => 'Receive a quote', 'slug' => 'receive-quote'],
            ['title' => 'Download Service', 'slug' => 'download-service'],
            ['title' => 'owayo Design Service', 'slug' => 'owayo-design-service'],
            ['title' => 'Club and School Rewards Program', 'slug' => 'club-school-rewards'],
            ['title' => 'How do I order?', 'slug' => 'how-do-i-order'],
            ['title' => 'Size Chart', 'slug' => 'size-chart'],
            ['title' => 'Production Time', 'slug' => 'production-time'],
            ['title' => 'Price List', 'slug' => 'price-list'],
            ['title' => 'Your owayo Shop', 'slug' => 'your-owayo-shop'],
        ];

        $sortOrder = 1;
        foreach ($supportPages as $pageData) {
            $page = Page::where('slug', $pageData['slug'])->first();
            if ($page) {
                MenuItem::firstOrCreate([
                    'menu_id' => $headerMenu->id,
                    'parent_id' => $supportMenuItem->id,
                    'title' => $pageData['title']
                ], [
                    'url' => null,
                    'page_id' => $page->id,
                    'description' => null,
                    'icon_class' => null,
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                    'target' => '_self'
                ]);
            }
            $sortOrder++;
        }

        // Create About Ricona menu
        $aboutMenuItem = MenuItem::firstOrCreate([
            'menu_id' => $headerMenu->id,
            'title' => 'About Ricona'
        ], [
            'url' => null,
            'page_id' => null,
            'description' => 'Learn more about Ricona',
            'icon_class' => 'bi bi-info-circle',
            'parent_id' => null,
            'sort_order' => 2,
            'is_active' => true,
            'target' => '_self'
        ]);

        // About Ricona submenu items
        $aboutPages = [
            ['title' => 'News', 'slug' => 'news'],
            ['title' => 'Tried and tested reviews', 'slug' => 'tried-and-tested-reviews'],
            ['title' => 'References', 'slug' => 'references'],
            ['title' => 'Design of the Day', 'slug' => 'design-of-the-day'],
            ['title' => 'Athletes and Teams', 'slug' => 'athletes-and-teams'],
            ['title' => 'Environmental Responsibility', 'slug' => 'environmental-responsibility'],
            ['title' => 'Inside', 'slug' => 'inside'],
        ];

        $sortOrder = 1;
        foreach ($aboutPages as $pageData) {
            $page = Page::where('slug', $pageData['slug'])->first();
            if ($page) {
                MenuItem::firstOrCreate([
                    'menu_id' => $headerMenu->id,
                    'parent_id' => $aboutMenuItem->id,
                    'title' => $pageData['title']
                ], [
                    'url' => null,
                    'page_id' => $page->id,
                    'description' => null,
                    'icon_class' => null,
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                    'target' => '_self'
                ]);
            }
            $sortOrder++;
        }

        // Create Contact menu
        $contactMenuItem = MenuItem::firstOrCreate([
            'menu_id' => $headerMenu->id,
            'title' => 'Contact'
        ], [
            'url' => null,
            'page_id' => null,
            'description' => 'Get in touch with us',
            'icon_class' => 'bi bi-envelope',
            'parent_id' => null,
            'sort_order' => 3,
            'is_active' => true,
            'target' => '_self'
        ]);

        // Contact submenu items
        $contactPages = [
            ['title' => 'Contact Form', 'slug' => 'contact-form'],
            ['title' => 'owayo Newsletter', 'slug' => 'owayo-newsletter'],
        ];

        $sortOrder = 1;
        foreach ($contactPages as $pageData) {
            $page = Page::where('slug', $pageData['slug'])->first();
            if ($page) {
                MenuItem::firstOrCreate([
                    'menu_id' => $headerMenu->id,
                    'parent_id' => $contactMenuItem->id,
                    'title' => $pageData['title']
                ], [
                    'url' => null,
                    'page_id' => $page->id,
                    'description' => null,
                    'icon_class' => null,
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                    'target' => '_self'
                ]);
            }
            $sortOrder++;
        }

        $this->command->info('Menu and menu items created successfully!');
    }
}