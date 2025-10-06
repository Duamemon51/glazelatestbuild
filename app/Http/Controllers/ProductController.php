<?php

namespace App\Http\Controllers;

use App\Models\ParentCategory;
use App\Models\Pattern;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Subcategory;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        // 3-level eager loading: Product → ProductType → Subcategory → ParentCategory
        $products = Product::with('productType.subcategory.parentCategory')->paginate(12);
        $productTypes = ProductType::with('subcategory.parentCategory')->orderBy('name')->get();
        $patterns = Pattern::active()->orderBy('name')->get();
        $designs = Design::where('is_active', true)->orderBy('name')->get();
    $parentCategories = ParentCategory::with('subcategories')->orderBy('name')->get();
    $subcategories = Subcategory::orderBy('name')->get();
    $productHierarchy = $this->buildHierarchy($parentCategories, $productTypes);

    return view('admin.products.index', compact('products', 'productTypes', 'patterns', 'designs', 'parentCategories', 'subcategories', 'productHierarchy'));
    }

    // Show form to create new product
    public function create()
    {
        $productTypes = ProductType::with('subcategory.parentCategory')->orderBy('name')->get();
        $patterns = Pattern::active()->orderBy('name')->get();
        $designs = Design::where('is_active', true)->orderBy('name')->get();
        $parentCategories = ParentCategory::with('subcategories')->orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();
        $productHierarchy = $this->buildHierarchy($parentCategories, $productTypes);

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.products.create-modal', compact('productTypes', 'patterns', 'designs', 'parentCategories', 'subcategories', 'productHierarchy'));
        }

        return view('admin.products.create', compact('productTypes', 'patterns', 'designs', 'parentCategories', 'subcategories', 'productHierarchy'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent_category_id' => 'required|exists:parent_categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'price' => 'required|numeric',
            'quantity' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'prices' => 'nullable|array',
            'prices.*.min_quantity' => 'required_with:prices|integer|min:1',
            'prices.*.price' => 'required_with:prices|numeric|min:0',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,avif|max:5120',
            'model_3d' => 'nullable|file|max:51200',
             'details' => 'nullable|string',
            'patterns' => 'nullable|array',
            'patterns.*' => 'exists:patterns,id',
            'designs' => 'nullable|array',
            'designs.*' => 'exists:designs,id',
        ]);

        $subcategory = Subcategory::with('parentCategory')->findOrFail($request->input('subcategory_id'));
        if ((string) $subcategory->parent_category_id !== (string) $request->input('parent_category_id')) {
            throw ValidationException::withMessages([
                'subcategory_id' => 'Selected subcategory does not belong to the chosen main category.',
            ]);
        }

        $productType = ProductType::findOrFail($request->input('product_type_id'));
        if ((string) $productType->subcategory_id !== (string) $subcategory->id) {
            throw ValidationException::withMessages([
                'product_type_id' => 'Selected product type does not belong to the chosen subcategory.',
            ]);
        }

        $data = $request->except(['parent_category_id', 'subcategory_id', 'patterns', 'designs']);

        $data['is_active'] = $request->boolean('is_active', true);

        if(isset($data['prices'])) {
            $data['prices'] = json_encode($data['prices']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products/images', 'public');
        }

        if ($request->hasFile('model_3d')) {
            $file = $request->file('model_3d');
            $data['model_3d'] = $this->storeModelFile($file);
        }

    $product = Product::create($data);

    $product->patterns()->sync($request->input('patterns', []));
    $product->designs()->sync($request->input('designs', []));

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    // Show form to edit product
    public function edit(Product $product)
    {
        $product->load(['patterns', 'designs']);
        $productTypes = ProductType::with('subcategory.parentCategory')->orderBy('name')->get();
        $patterns = Pattern::active()->orderBy('name')->get();
        $designs = Design::where('is_active', true)->orderBy('name')->get();
        $parentCategories = ParentCategory::with('subcategories')->orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();
        $productHierarchy = $this->buildHierarchy($parentCategories, $productTypes);

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.products.edit-modal', compact('product', 'productTypes', 'patterns', 'designs', 'parentCategories', 'subcategories', 'productHierarchy'));
        }

        return view('admin.products.edit', compact('product', 'productTypes', 'patterns', 'designs', 'parentCategories', 'subcategories', 'productHierarchy'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        // Filter out empty price entries
        if ($request->has('prices')) {
            $prices = array_filter($request->input('prices'), function($price) {
                return !empty($price['min_quantity']) && !empty($price['price']);
            });
            $request->merge(['prices' => array_values($prices)]);
        }
        
        $request->validate([
            'name' => 'required',
            'parent_category_id' => 'required|exists:parent_categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'price' => 'required|numeric',
            'quantity' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'prices' => 'nullable|array',
            'prices.*.min_quantity' => 'required_with:prices|integer|min:1',
            'prices.*.price' => 'required_with:prices|numeric|min:0',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,avif|max:5120',
            'model_3d' => 'nullable|file|max:51200',
             'details' => 'nullable|string',
            'patterns' => 'nullable|array',
            'patterns.*' => 'exists:patterns,id',
            'designs' => 'nullable|array',
            'designs.*' => 'exists:designs,id',
        ]);

        $subcategory = Subcategory::with('parentCategory')->findOrFail($request->input('subcategory_id'));
        if ((string) $subcategory->parent_category_id !== (string) $request->input('parent_category_id')) {
            throw ValidationException::withMessages([
                'subcategory_id' => 'Selected subcategory does not belong to the chosen main category.',
            ]);
        }

        $productType = ProductType::findOrFail($request->input('product_type_id'));
        if ((string) $productType->subcategory_id !== (string) $subcategory->id) {
            throw ValidationException::withMessages([
                'product_type_id' => 'Selected product type does not belong to the chosen subcategory.',
            ]);
        }

        $data = $request->except(['parent_category_id', 'subcategory_id', 'patterns', 'designs']);

        $data['is_active'] = $request->boolean('is_active', $product->is_active);

        if(isset($data['prices'])) {
            $data['prices'] = json_encode($data['prices']);
        }

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products/images', 'public');
        }

        if ($request->hasFile('model_3d')) {
            if ($product->model_3d && Storage::disk('public')->exists($product->model_3d)) {
                Storage::disk('public')->delete($product->model_3d);
            }

            $file = $request->file('model_3d');
            $data['model_3d'] = $this->storeModelFile($file);
        }

    $product->update($data);
    
    // Sync patterns and designs
    $patternsToSync = $request->input('patterns', []);
    $designsToSync = $request->input('designs', []);
    
    // Use detach/attach instead of sync to avoid SQL ambiguity
    $product->patterns()->detach();
    if (!empty($patternsToSync)) {
        $product->patterns()->attach($patternsToSync);
    }
    
    $product->designs()->detach();
    if (!empty($designsToSync)) {
        $product->designs()->attach($designsToSync);
    }
    
    return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    // Delete product
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        if ($product->model_3d && Storage::disk('public')->exists($product->model_3d)) {
            Storage::disk('public')->delete($product->model_3d);
        }

        $product->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
    // Show 3D model of product
    public function show3D(Product $product)
    {
        if (!$product->model_3d) {
            abort(404);
        }

        $modelExtension = strtolower(pathinfo($product->model_3d, PATHINFO_EXTENSION));

        return view('admin.products.show3d', [
            'product' => $product,
            'modelExtension' => $modelExtension,
            'previewableExtensions' => config('uploads.previewable_model_extensions', ['glb', 'gltf']),
        ]);
    }
    public function show($id)
    {
        // Fetch product by ID with relationships
        $product = Product::with(['productType.subcategory.parentCategory', 'patterns', 'designs'])->findOrFail($id);

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.products.show-modal', compact('product'));
        }

        // Ensure prices is always an array for regular view
        if (!is_array($product->prices)) {
            $product->prices = $product->prices ? json_decode($product->prices, true) : [];
        }

        // Send product to view
        return view('product-page', compact('product'));
    }


    private function buildHierarchy($parentCategories, $productTypes)
    {
        return $parentCategories->map(function ($parent) use ($productTypes) {
            return [
                'id' => $parent->id,
                'name' => $parent->name,
                'subcategories' => $parent->subcategories->map(function ($subcategory) use ($productTypes) {
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name,
                        'product_types' => $productTypes->where('subcategory_id', $subcategory->id)
                            ->map(function ($type) {
                                return [
                                    'id' => $type->id,
                                    'name' => $type->name,
                                ];
                            })->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->values()->all();
    }


    private function storeModelFile($file): string
    {
        $allowedExtensions = config('uploads.product_model_extensions', []);
        $extension = strtolower($file->getClientOriginalExtension());

        if ($allowedExtensions && !in_array($extension, $allowedExtensions, true)) {
            $readable = implode(', ', $allowedExtensions);
            throw ValidationException::withMessages([
                'model_3d' => "Unsupported 3D model format. Allowed extensions: {$readable}.",
            ]);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName);
        $timestamp = now()->timestamp;
        $fileName = ($safeName ?: 'model') . "-{$timestamp}.{$extension}";

        return $file->storeAs('products/models', $fileName, 'public');
    }
}