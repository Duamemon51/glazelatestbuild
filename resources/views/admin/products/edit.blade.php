@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Product</h1>
                <p class="text-sm text-gray-500">Update product details, pricing tiers, imagery, and 3D assets.</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Back to list</a>
        </div>
        <div class="p-6">
            @include('admin.products.partials.form', [
                'formId' => 'editProductForm',
                'action' => route('admin.products.update', $product),
                'method' => 'PUT',
                'fieldPrefix' => 'edit',
                'showCancel' => false,
                'submitLabel' => 'Update Product',
                'product' => $product,
                'productTypes' => $productTypes,
                'parentCategories' => $parentCategories,
                'subcategories' => $subcategories,
                'patterns' => $patterns,
                'designs' => $designs,
                'selectedPatternIds' => $product->patterns->pluck('id')->all(),
                'selectedDesignIds' => $product->designs->pluck('id')->all(),
                'formClass' => 'space-y-6',
                'footerClasses' => 'flex justify-end space-x-3 pt-4 border-t border-gray-200'
            ])
            @include('admin.products.partials.pricing-scripts', ['fieldPrefix' => 'edit'])
            @include('admin.products.partials.hierarchy-scripts', [
                'fieldPrefix' => 'edit',
                'hierarchy' => $productHierarchy,
            ])
            @include('admin.products.partials.select-all-scripts')
        </div>
    </div>
</div>
@endsection
