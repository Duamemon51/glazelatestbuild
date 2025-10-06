<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentCategory;
use Illuminate\Support\Facades\Storage;

class ParentCategoryController extends Controller
{
    public function index()
    {
        $parents = ParentCategory::with('productTypes')->paginate(12);
        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.parents.create-modal');
        }

        return view('admin.parents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:parent_categories,name',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120',
            'home_image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120',
            'category_image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120'
        ]);

        $imagePath = null;
        $homeImagePath = null;
        $categoryImagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('parent_categories', 'public');
        }
        if ($request->hasFile('home_image')) {
            $homeImagePath = $request->file('home_image')->store('parent_categories/home', 'public');
        }
        if ($request->hasFile('category_image')) {
            $categoryImagePath = $request->file('category_image')->store('parent_categories/category', 'public');
        }

        ParentCategory::create([
            'name' => $request->name,
            'image' => $imagePath,
            'home_image' => $homeImagePath,
            'category_image' => $categoryImagePath
        ]);

        return redirect()->route('admin.parents.index')
                         ->with('success', 'Parent Category created successfully');
    }

    public function show(ParentCategory $parent)
    {
        $parent->load('productTypes');

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.parents.show-modal', compact('parent'));
        }

        return view('admin.parents.show', compact('parent'));
    }

    public function edit(ParentCategory $parent)
    {
        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.parents.edit-modal', compact('parent'));
        }

        return view('admin.parents.edit', compact('parent'));
    }

    public function update(Request $request, ParentCategory $parent)
    {
        $request->validate([
            'name' => 'required|unique:parent_categories,name,' . $parent->id,
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120',
            'home_image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120',
            'category_image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120'
        ]);

        $imagePath = $parent->image;
        $homeImagePath = $parent->home_image;
        $categoryImagePath = $parent->category_image;
        if ($request->hasFile('image')) {
            if ($parent->image && Storage::disk('public')->exists($parent->image)) {
                Storage::disk('public')->delete($parent->image);
            }
            $imagePath = $request->file('image')->store('parent_categories', 'public');
        }

        if ($request->hasFile('home_image')) {
            if ($parent->home_image && Storage::disk('public')->exists($parent->home_image)) {
                Storage::disk('public')->delete($parent->home_image);
            }
            $homeImagePath = $request->file('home_image')->store('parent_categories/home', 'public');
        }

        if ($request->hasFile('category_image')) {
            if ($parent->category_image && Storage::disk('public')->exists($parent->category_image)) {
                Storage::disk('public')->delete($parent->category_image);
            }
            $categoryImagePath = $request->file('category_image')->store('parent_categories/category', 'public');
        }

        $parent->update([
            'name' => $request->name,
            'image' => $imagePath,
            'home_image' => $homeImagePath,
            'category_image' => $categoryImagePath
        ]);

        return redirect()->route('admin.parents.index')
                         ->with('success', 'Parent Category updated successfully');
    }

    public function destroy(ParentCategory $parent)
    {
        if ($parent->image && Storage::disk('public')->exists($parent->image)) {
            Storage::disk('public')->delete($parent->image);
        }
        if ($parent->home_image && Storage::disk('public')->exists($parent->home_image)) {
            Storage::disk('public')->delete($parent->home_image);
        }
        if ($parent->category_image && Storage::disk('public')->exists($parent->category_image)) {
            Storage::disk('public')->delete($parent->category_image);
        }
        $parent->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Parent Category deleted successfully.']);
        }

        return redirect()->route('admin.parents.index')
                         ->with('success', 'Parent Category deleted successfully');
    }
}
