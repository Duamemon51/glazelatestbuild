<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentCategory;
use App\Models\Page;

class HomeController extends Controller
{
    // Homepage
    public function index()
    {
        // Fetch all parent categories from DB
        $parentCategories = ParentCategory::all();

        // Load a page with content sections (for now, load page ID 40 which has content)
        // In production, you might want to load a page with slug 'home' or the first published page
        $homePage = Page::with(['contentSections' => function($query) {
            $query->orderBy('sort_order');
        }])->find(40); // Temporarily load page ID 40

        // Return the home view with categories and page content
        return view('welcome', compact('parentCategories', 'homePage'));
    }
}
