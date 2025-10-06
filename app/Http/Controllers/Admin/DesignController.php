<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Design;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    /** Display all designs */
    public function index()
    {
        $designs = Design::with('products')->paginate(12);
        return view('admin.designs.index', compact('designs'));
    }

    /** Show form to create a new design */
    public function create()
    {
        $products = Product::where('is_active', true)->get();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.designs.create-modal', compact('products'));
        }

        return view('admin.designs.create', compact('products'));
    }

    /** Store new design */
    public function store(Request $request)
    {
     $request->validate([
    'front_image'  => 'nullable|mimes:jpg,jpeg,png,avif',
    'back_image'   => 'nullable|mimes:jpg,jpeg,png,avif',
    'left_image'   => 'nullable|mimes:jpg,jpeg,png,avif',
    'right_image'  => 'nullable|mimes:jpg,jpeg,png,avif',
    'preview_img'  => 'nullable|mimes:jpg,jpeg,png,avif', // added
    'name'         => 'required|string|max:255',
    'products.*'   => 'nullable|exists:products,id',
    'is_active'    => 'nullable|boolean',
     'is_unique'    => 'nullable|boolean'
]);



        $data = $request->only(['name','is_active', 'is_unique']);

        // Upload images
      $data = $request->only(['name','is_active', 'is_unique']);

// Upload images including preview_img
foreach (['front_image','back_image','left_image','right_image','preview_img'] as $img) {
    if($request->hasFile($img)){
        $data[$img] = $request->file($img)->store('designs', 'public');
    }
}

$design = Design::create($data);

        // Attach products (pivot)
        if($request->has('products')){
            $design->products()->sync($request->products);
        }

        return redirect()->route('admin.designs.index')->with('success','Design created successfully!');
    }

    /** Show edit form */
    public function edit($id)
    {
        $design = Design::with('products')->findOrFail($id);
        $products = Product::where('is_active', true)->get();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.designs.edit-modal', compact('design', 'products'));
        }

        return view('admin.designs.edit', compact('design','products'));
    }

    /** Update design */
    public function update(Request $request, $id)
    {
        $design = Design::findOrFail($id);
$request->validate([
    'front_image'  => 'nullable|mimes:jpg,jpeg,png,avif',
    'back_image'   => 'nullable|mimes:jpg,jpeg,png,avif',
    'left_image'   => 'nullable|mimes:jpg,jpeg,png,avif',
    'right_image'  => 'nullable|mimes:jpg,jpeg,png,avif',
    'preview_img'  => 'nullable|mimes:jpg,jpeg,png,avif', // added
    'name'         => 'required|string|max:255',
    'products.*'   => 'nullable|exists:products,id',
    'is_active'    => 'nullable|boolean',
     'is_unique'    => 'nullable|boolean', 
]);


        $data = $request->only(['name','is_active', 'is_unique']);

        // Update images if uploaded
       $data = $request->only(['name','is_active', 'is_unique']);

// Upload images including preview_img
// Update images if uploaded
foreach (['front_image','back_image','left_image','right_image','preview_img'] as $img) {
    if($request->hasFile($img)){
        if($design->$img) Storage::disk('public')->delete($design->$img);
        $data[$img] = $request->file($img)->store('designs','public');
    }
}

$design->update($data);


      

        // Sync products
        if($request->has('products')){
            $design->products()->sync($request->products);
        } else {
            $design->products()->sync([]);
        }

        return redirect()->route('admin.designs.index')->with('success','Design updated successfully!');
    }

    /** Delete design */
    public function destroy($id)
    {
        $design = Design::findOrFail($id);

        // Delete images from storage
        foreach (['front_image','back_image','left_image','right_image','preview_img'] as $img) {
            if($design->$img) Storage::disk('public')->delete($design->$img);
        }

        $design->delete();

        return redirect()->route('admin.designs.index')->with('success','Design deleted successfully!');
    }
}
