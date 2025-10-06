<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pattern;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class PatternController extends Controller
{
    public function index()
    {
        $patterns = Pattern::with('products')->paginate(15);
        return view('admin.patterns.index', compact('patterns'));
    }

    public function create()
    {
        $products = Product::all();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.patterns.create-modal', compact('products'));
        }

        return view('admin.patterns.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'svg_content' => 'required|string',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'is_active' => 'boolean'
        ]);

        // Validate SVG content
        if (!$this->isValidSvg($request->svg_content)) {
            return back()->withErrors(['svg_content' => 'Invalid SVG content provided.'])->withInput();
        }

        $pattern = Pattern::create([
            'name' => $request->name,
            'svg_content' => $request->svg_content,
            'is_active' => $request->has('is_active')
        ]);

        // Attach products if provided
        if ($request->has('product_ids') && is_array($request->product_ids)) {
            $pattern->products()->attach($request->product_ids);
        }

        return redirect()->route('admin.patterns.index')
                         ->with('success', 'Pattern created successfully');
    }

    public function show(Pattern $pattern)
    {
        $pattern->load('products');

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.patterns.show-modal', compact('pattern'));
        }

        return view('admin.patterns.show', compact('pattern'));
    }

    public function edit(Pattern $pattern)
    {
        $pattern->load('products');
        $products = Product::all();

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.patterns.edit-modal', compact('pattern', 'products'));
        }

        return view('admin.patterns.edit', compact('pattern', 'products'));
    }

    public function update(Request $request, Pattern $pattern)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'svg_content' => 'required|string',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'is_active' => 'boolean'
        ]);

        // Validate SVG content
        if (!$this->isValidSvg($request->svg_content)) {
            return back()->withErrors(['svg_content' => 'Invalid SVG content provided.'])->withInput();
        }

        $pattern->update([
            'name' => $request->name,
            'svg_content' => $request->svg_content,
            'is_active' => $request->has('is_active')
        ]);

        // Sync products
        if ($request->has('product_ids') && is_array($request->product_ids)) {
            $pattern->products()->sync($request->product_ids);
        } else {
            $pattern->products()->detach();
        }

        return redirect()->route('admin.patterns.index')
                         ->with('success', 'Pattern updated successfully');
    }

    public function destroy(Pattern $pattern)
    {
        $pattern->delete();

        return redirect()->route('admin.patterns.index')
                         ->with('success', 'Pattern deleted successfully');
    }

    /**
     * Validate SVG content
     */
    private function isValidSvg($content)
    {
        // Basic SVG validation
        if (empty($content)) {
            return false;
        }

        // Check if it starts with SVG tag
        if (!preg_match('/<svg[^>]*>/i', $content)) {
            return false;
        }

        // Check if it has closing SVG tag
        if (!preg_match('/<\/svg>/i', $content)) {
            return false;
        }

        // Check for potentially dangerous content
        $dangerous = ['<script', 'javascript:', 'onload=', 'onerror='];
        foreach ($dangerous as $danger) {
            if (stripos($content, $danger) !== false) {
                return false;
            }
        }

        return true;
    }
}