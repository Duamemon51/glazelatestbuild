<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductType;
use App\Models\ParentCategory; // 👈 ye add karein
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'productType'])->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = ParentCategory::all(); // 👈 ab ParentCategory se load hoga
        $productTypes = ProductType::all();
        return view('admin.categories.create', compact('parents', 'productTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:parent_categories,id', // 👈 validate parent_categories se
            'product_type_id' => 'required|exists:product_types,id',
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'product_type_id' => $request->product_type_id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        $parents = ParentCategory::all(); // 👈 yahan bhi ParentCategory
        $productTypes = ProductType::all();
        return view('admin.categories.edit', compact('category', 'parents', 'productTypes'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:parent_categories,id',
            'product_type_id' => 'required|exists:product_types,id',
        ]);

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'product_type_id' => $request->product_type_id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
