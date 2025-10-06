<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; 

use App\Models\ProductExample;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductExampleController extends Controller
{
    // List all examples
    public function index()
    {
        $examples = ProductExample::with('productType')->paginate(12);
        return view('admin.examples.index', compact('examples'));
    }

    // Show form to create new example
    public function create()
    {
        $productTypes = ProductType::all();
        return view('admin.examples.form', compact('productTypes'));
    }

    // Store new example
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120',
            'model_3d' => 'nullable|file',
        ]);

        $data = $request->all();

        // Upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('examples/images', 'public');
        }

        // Upload 3D model with extension check
        if ($request->hasFile('model_3d')) {
            $file = $request->file('model_3d');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['model_3d' => 'The 3D model must be a .glb or .gltf file.']);
            }
            $data['model_3d'] = $file->store('examples/models', 'public');
        }

        ProductExample::create($data);

        return redirect()->route('admin.examples.index')->with('success', 'Example created successfully.');
    }

    // Show example details
    public function show(ProductExample $example)
    {
        $example->load('productType');

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.examples.show-modal', compact('example'));
        }

        return view('admin.examples.show', compact('example'));
    }

    // Show edit form
    public function edit(ProductExample $example)
    {
        $productTypes = ProductType::all();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.examples.edit-modal', compact('example', 'productTypes'));
        }

        return view('admin.examples.form', compact('example', 'productTypes'));
    }

    // Update example
    public function update(Request $request, ProductExample $example)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif|max:5120',
            'model_3d' => 'nullable|file',
        ]);

        $data = $request->all();

        // Replace image if uploaded
        if ($request->hasFile('image')) {
            if ($example->image && Storage::disk('public')->exists($example->image)) {
                Storage::disk('public')->delete($example->image);
            }
            $data['image'] = $request->file('image')->store('examples/images', 'public');
        }

        // Replace 3D model if uploaded
        if ($request->hasFile('model_3d')) {
            $file = $request->file('model_3d');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['model_3d' => 'The 3D model must be a .glb or .gltf file.']);
            }
            if ($example->model_3d && Storage::disk('public')->exists($example->model_3d)) {
                Storage::disk('public')->delete($example->model_3d);
            }
            $data['model_3d'] = $file->store('examples/models', 'public');
        }

        $example->update($data);

        return redirect()->route('admin.examples.index')->with('success', 'Example updated successfully.');
    }

    // Delete example
    public function destroy(ProductExample $example)
    {
        if ($example->image && Storage::disk('public')->exists($example->image)) {
            Storage::disk('public')->delete($example->image);
        }

        if ($example->model_3d && Storage::disk('public')->exists($example->model_3d)) {
            Storage::disk('public')->delete($example->model_3d);
        }

        $example->delete();

        return redirect()->route('admin.examples.index')->with('success', 'Example deleted successfully.');
    }
    // Show 3D model of example
public function show3D(ProductExample $example)
{
    return view('admin.examples.show3d', compact('example'));
}

}
