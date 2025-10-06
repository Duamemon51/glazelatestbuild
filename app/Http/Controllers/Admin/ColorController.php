<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminColor;
use App\Models\ColorCategory;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AdminColor::with('category');
        
        if ($request->filled('category_id')) {
            $query->where('color_category_id', $request->category_id);
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $colors = $query->paginate(12);
        $categories = ColorCategory::all();
        
        return view('admin.colors.index', compact('colors', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ColorCategory::all();
        $coloringSystems = [
            'pantone_coated' => 'Pantone Coated',
            'pantone_uncoated' => 'Pantone Uncoated',
            'hks_k' => 'HKS K',
            'hks_n' => 'HKS N',
            'ral' => 'RAL',
        ];
        
        if (request()->ajax()) {
            return view('admin.colors.create-modal', compact('categories', 'coloringSystems'));
        }
        return view('admin.colors.create', compact('categories', 'coloringSystems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'color_category_id' => 'required|exists:color_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'rgb_r' => 'required|integer|between:0,255',
            'rgb_g' => 'required|integer|between:0,255',
            'rgb_b' => 'required|integer|between:0,255',
            'closest_association' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'coloring_system' => 'required|in:pantone_coated,pantone_uncoated,hks_k,hks_n,ral',
        ]);

        AdminColor::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Color created successfully.']);
        }

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminColor $color)
    {
        $color->load('category');
        return view('admin.colors.show', compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminColor $color)
    {
        $categories = ColorCategory::all();
        $coloringSystems = [
            'pantone_coated' => 'Pantone Coated',
            'pantone_uncoated' => 'Pantone Uncoated',
            'hks_k' => 'HKS K',
            'hks_n' => 'HKS N',
            'ral' => 'RAL',
        ];
        
        return view('admin.colors.edit', compact('color', 'categories', 'coloringSystems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdminColor $color)
    {
        $request->validate([
            'color_category_id' => 'required|exists:color_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'rgb_r' => 'required|integer|between:0,255',
            'rgb_g' => 'required|integer|between:0,255',
            'rgb_b' => 'required|integer|between:0,255',
            'closest_association' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'coloring_system' => 'required|in:pantone_coated,pantone_uncoated,hks_k,hks_n,ral',
        ]);

        $color->update($request->all());

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminColor $color)
    {
        $color->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Color deleted successfully.']);
        }

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color deleted successfully.');
    }
}
