<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ColorCategory;
use Illuminate\Http\Request;

class ColorCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ColorCategory::withCount('colors')->paginate(10);
        return view('admin.color-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax()) {
            return view('admin.color-categories.create-modal');
        }
        return view('admin.color-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ColorCategory::create($request->only(['name', 'description']));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Color category created successfully.']);
        }

        return redirect()->route('admin.color-categories.index')
            ->with('success', 'Color category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ColorCategory $colorCategory)
    {
        $colorCategory->load('colors');
        return view('admin.color-categories.show', compact('colorCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ColorCategory $colorCategory)
    {
        return view('admin.color-categories.edit', compact('colorCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ColorCategory $colorCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $colorCategory->update($request->only(['name', 'description']));

        return redirect()->route('admin.color-categories.index')
            ->with('success', 'Color category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ColorCategory $colorCategory)
    {
        $colorCategory->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Color category deleted successfully.']);
        }

        return redirect()->route('admin.color-categories.index')
            ->with('success', 'Color category deleted successfully.');
    }
}
