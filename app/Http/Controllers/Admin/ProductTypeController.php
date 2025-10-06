<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\Subcategory;
use App\Models\ParentCategory;
use App\Models\ProductExample;
use Illuminate\Support\Facades\Storage;

class ProductTypeController extends Controller
{
    // List all product types
    public function index()
    {
        $types = ProductType::with('subcategory.parentCategory')->paginate(12);
        return view('admin.product_types.index', compact('types'));
    }

    // Show create form
    public function create()
    {
        $parentCategories = ParentCategory::all();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.product_types.create-modal', compact('parentCategories'));
        }

        return view('admin.product_types.create', compact('parentCategories'));
    }

    // Store product type
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:product_types,name',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('product_types', 'public') : null;

        ProductType::create([
            'name' => $request->name,
            'subcategory_id' => $request->subcategory_id,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.product-types.index')
                         ->with('success', 'Product Type created successfully');
    }

    // Show product type details
    public function show(ProductType $productType)
    {
        $productType->load('subcategory.parentCategory');

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.product_types.show-modal', compact('productType'));
        }

        return view('admin.product_types.show', compact('productType'));
    }

    // Show edit form
    public function edit(ProductType $productType)
    {
        $parentCategories = ParentCategory::all();
        $subcategories = Subcategory::where('parent_category_id', $productType->subcategory->parent_category_id)->get();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.product_types.edit-modal', compact('productType', 'parentCategories', 'subcategories'));
        }

        return view('admin.product_types.edit', compact('productType', 'parentCategories', 'subcategories'));
    }

    // Update product type
    public function update(Request $request, ProductType $productType)
    {
        $request->validate([
            'name' => 'required|unique:product_types,name,' . $productType->id,
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120',
        ]);

        $imagePath = $productType->image;

        if ($request->hasFile('image')) {
            if ($productType->image && Storage::disk('public')->exists($productType->image)) {
                Storage::disk('public')->delete($productType->image);
            }
            $imagePath = $request->file('image')->store('product_types', 'public');
        }

        $productType->update([
            'name' => $request->name,
            'subcategory_id' => $request->subcategory_id,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.product-types.index')
                         ->with('success', 'Product Type updated successfully');
    }

    // Delete product type
    public function destroy(ProductType $productType)
    {
        if ($productType->image && Storage::disk('public')->exists($productType->image)) {
            Storage::disk('public')->delete($productType->image);
        }

        $productType->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product Type deleted successfully.']);
        }

        return redirect()->route('admin.product-types.index')
                         ->with('success', 'Product Type deleted successfully');
    }

 public function showProductType($productTypeId)
{
    $firstProductType = ProductType::with('products', 'subcategory.parentCategory')->find($productTypeId);
    
    if (!$firstProductType) {
        abort(404);
    }

    $parentCategory = $firstProductType->subcategory->parentCategory ?? null;

    $products = $firstProductType->products ?? []; // Products linked to this type
    $examples = ProductExample::where('product_type_id', $firstProductType->id)->get();

    $productTypes = ProductType::all(); // For "SEE OTHER PRODUCTS" section

    $colors = [
        1 => '#FF5733',
        2 => '#33C1FF',
        3 => '#28A745',
        4 => '#FFC300',
        5 => '#8E44AD',
    ];

    return view('product_details', compact(
        'firstProductType', 
        'parentCategory', 
        'products', 
        'examples', 
        'productTypes', 
        'colors'
    ));
}
// For showing products by parent category
public function showParentCategory($parentCategoryId)
{
    $parentCategory = ParentCategory::with('subcategories.productTypes.products')->findOrFail($parentCategoryId);

    $productTypes = $parentCategory->subcategories->flatMap->productTypes;

    $colors = [
        1 => '#FF5733',
        2 => '#33C1FF',
        3 => '#28A745',
        4 => '#FFC300',
        5 => '#8E44AD',
    ];

    return view('category', compact('parentCategory', 'productTypes', 'colors'));
}

public function showCategory($parentCategoryId)
{
    $firstProductType = ProductType::whereHas('subcategory', function($q) use ($parentCategoryId) {
        $q->where('parent_category_id', $parentCategoryId);
    })->with('products')->first();

    if (!$firstProductType) {
        abort(404, 'No product type found for this category');
    }

    $parentCategory = ParentCategory::find($parentCategoryId);
    $products = $firstProductType->products ?? [];
    $examples = ProductExample::where('product_type_id', $firstProductType->id)->get();
    $productTypes = ProductType::all();

    $colors = [
        1 => '#FF5733',
        2 => '#33C1FF',
        3 => '#28A745',
        4 => '#FFC300',
        5 => '#8E44AD',
    ];

    return view('product_details', compact(
        'firstProductType', 
        'parentCategory', 
        'products', 
        'examples', 
        'productTypes', 
        'colors'
    ));
}

}
