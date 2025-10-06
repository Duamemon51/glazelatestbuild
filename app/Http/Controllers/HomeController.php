<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentCategory;

class HomeController extends Controller
{
    // Homepage
    public function index()
    {
        // Fetch all parent categories from DB
        $parentCategories = ParentCategory::all();

        // Return the home view with categories
        return view('welcome', compact('parentCategories'));
    }
}
