<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\ParentCategory;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('parentCategory')->paginate(12);
        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $parentCategories = ParentCategory::all();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.subcategories.create-modal', compact('parentCategories'));
        }

        return view('admin.subcategories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:subcategories,name,NULL,id,parent_category_id,' . $request->parent_category_id,
            'parent_category_id' => 'required|exists:parent_categories,id'
        ]);

        Subcategory::create($request->only('name', 'parent_category_id'));

        return redirect()->route('admin.subcategories.index')
                         ->with('success', 'Subcategory created successfully');
    }

    public function show(Subcategory $subcategory)
    {
        $subcategory->load('parentCategory');

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.subcategories.show-modal', compact('subcategory'));
        }

        return view('admin.subcategories.show', compact('subcategory'));
    }

    public function edit(Subcategory $subcategory)
    {
        $parentCategories = ParentCategory::all();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.subcategories.edit-modal', compact('subcategory', 'parentCategories'));
        }

        return view('admin.subcategories.edit', compact('subcategory', 'parentCategories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|unique:subcategories,name,' . $subcategory->id . ',id,parent_category_id,' . $request->parent_category_id,
            'parent_category_id' => 'required|exists:parent_categories,id'
        ]);

        $subcategory->update($request->only('name', 'parent_category_id'));

        return redirect()->route('admin.subcategories.index')
                         ->with('success', 'Subcategory updated successfully');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Subcategory deleted successfully.']);
        }

        return redirect()->route('admin.subcategories.index')
                         ->with('success', 'Subcategory deleted successfully');
    }

    // AJAX: get subcategories by parent category
    public function getByParent($parentId)
    {
        $subcategories = Subcategory::where('parent_category_id', $parentId)->get();
        return response()->json($subcategories);
    }
}
