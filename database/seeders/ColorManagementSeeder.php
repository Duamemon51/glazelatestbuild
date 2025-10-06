<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ColorCategory;
use App\Models\AdminColor;

class ColorManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Color Categories
        $primaryColors = ColorCategory::create([
            'name' => 'Primary Colors',
            'description' => 'Basic primary colors - Red, Blue, Yellow and their variations'
        ]);

        $pastels = ColorCategory::create([
            'name' => 'Pastels',
            'description' => 'Soft, muted colors perfect for gentle designs'
        ]);

        $corporate = ColorCategory::create([
            'name' => 'Corporate Colors',
            'description' => 'Professional colors commonly used in business branding'
        ]);

        $vibrant = ColorCategory::create([
            'name' => 'Vibrant Colors',
            'description' => 'Bold, bright colors that make a statement'
        ]);

        // Create Colors for Primary Colors Category
        AdminColor::create([
            'color_category_id' => $primaryColors->id,
            'name' => 'Classic Red',
            'code' => 'PMS 186 C',
            'rgb_r' => 200,
            'rgb_g' => 16,
            'rgb_b' => 46,
            'closest_association' => 'Fire Engine Red',
            'description' => 'A bold, classic red perfect for attention-grabbing designs',
            'coloring_system' => 'pantone_coated'
        ]);

        AdminColor::create([
            'color_category_id' => $primaryColors->id,
            'name' => 'Ocean Blue',
            'code' => 'PMS 286 C',
            'rgb_r' => 0,
            'rgb_g' => 32,
            'rgb_b' => 96,
            'closest_association' => 'Deep Ocean',
            'description' => 'A deep, trustworthy blue reminiscent of ocean depths',
            'coloring_system' => 'pantone_coated'
        ]);

        AdminColor::create([
            'color_category_id' => $primaryColors->id,
            'name' => 'Sunshine Yellow',
            'code' => 'PMS 116 C',
            'rgb_r' => 255,
            'rgb_g' => 209,
            'rgb_b' => 0,
            'closest_association' => 'Bright Sunshine',
            'description' => 'A cheerful, energetic yellow that brightens any design',
            'coloring_system' => 'pantone_coated'
        ]);

        // Create Colors for Pastels Category
        AdminColor::create([
            'color_category_id' => $pastels->id,
            'name' => 'Soft Pink',
            'code' => 'RAL 3015',
            'rgb_r' => 234,
            'rgb_g' => 191,
            'rgb_b' => 203,
            'closest_association' => 'Cherry Blossom',
            'description' => 'A gentle, romantic pink perfect for delicate designs',
            'coloring_system' => 'ral'
        ]);

        AdminColor::create([
            'color_category_id' => $pastels->id,
            'name' => 'Mint Green',
            'code' => 'RAL 6019',
            'rgb_r' => 189,
            'rgb_g' => 218,
            'rgb_b' => 187,
            'closest_association' => 'Fresh Mint',
            'description' => 'A refreshing, calming green with mint undertones',
            'coloring_system' => 'ral'
        ]);

        AdminColor::create([
            'color_category_id' => $pastels->id,
            'name' => 'Sky Blue',
            'code' => 'RAL 5012',
            'rgb_r' => 172,
            'rgb_g' => 213,
            'rgb_b' => 242,
            'closest_association' => 'Clear Sky',
            'description' => 'A peaceful, airy blue like a clear summer sky',
            'coloring_system' => 'ral'
        ]);

        // Create Colors for Corporate Category
        AdminColor::create([
            'color_category_id' => $corporate->id,
            'name' => 'Corporate Navy',
            'code' => 'HKS 78 K',
            'rgb_r' => 27,
            'rgb_g' => 49,
            'rgb_b' => 77,
            'closest_association' => 'Business Suit',
            'description' => 'A professional, authoritative navy blue for corporate use',
            'coloring_system' => 'hks_k'
        ]);

        AdminColor::create([
            'color_category_id' => $corporate->id,
            'name' => 'Charcoal Gray',
            'code' => 'HKS 95 K',
            'rgb_r' => 88,
            'rgb_g' => 88,
            'rgb_b' => 90,
            'closest_association' => 'Business Gray',
            'description' => 'A sophisticated gray that conveys professionalism',
            'coloring_system' => 'hks_k'
        ]);

        AdminColor::create([
            'color_category_id' => $corporate->id,
            'name' => 'Forest Green',
            'code' => 'HKS 57 K',
            'rgb_r' => 45,
            'rgb_g' => 87,
            'rgb_b' => 44,
            'closest_association' => 'Pine Forest',
            'description' => 'A strong, stable green representing growth and reliability',
            'coloring_system' => 'hks_k'
        ]);

        // Create Colors for Vibrant Category
        AdminColor::create([
            'color_category_id' => $vibrant->id,
            'name' => 'Electric Purple',
            'code' => 'PMS 2592 U',
            'rgb_r' => 134,
            'rgb_g' => 38,
            'rgb_b' => 195,
            'closest_association' => 'Neon Purple',
            'description' => 'A bold, electric purple that demands attention',
            'coloring_system' => 'pantone_uncoated'
        ]);

        AdminColor::create([
            'color_category_id' => $vibrant->id,
            'name' => 'Lime Green',
            'code' => 'PMS 382 U',
            'rgb_r' => 174,
            'rgb_g' => 211,
            'rgb_b' => 25,
            'closest_association' => 'Lime Fruit',
            'description' => 'A vibrant, energetic lime green perfect for modern designs',
            'coloring_system' => 'pantone_uncoated'
        ]);

        AdminColor::create([
            'color_category_id' => $vibrant->id,
            'name' => 'Hot Orange',
            'code' => 'PMS 1665 U',
            'rgb_r' => 255,
            'rgb_g' => 103,
            'rgb_b' => 31,
            'closest_association' => 'Sunset Orange',
            'description' => 'A fiery, energetic orange that radiates warmth and excitement',
            'coloring_system' => 'pantone_uncoated'
        ]);
    }
}
