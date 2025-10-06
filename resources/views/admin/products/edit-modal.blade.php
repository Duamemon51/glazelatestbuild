<div class="mb-4">
    <h3 class="text-lg font-semibold text-gray-900">Edit Product</h3>
</div>

@include('admin.products.partials.form', [
    'formId' => 'editProductForm',
    'action' => route('admin.products.update', $product),
    'method' => 'PUT',
    'fieldPrefix' => 'edit',
    'showCancel' => true,
    'cancelHandler' => 'closeEditModal()',
    'submitLabel' => 'Update Product',
    'product' => $product,
    'productTypes' => $productTypes,
    'parentCategories' => $parentCategories,
    'subcategories' => $subcategories,
    'patterns' => $patterns,
    'designs' => $designs,
    'selectedPatternIds' => $product->patterns->pluck('id')->all(),
    'selectedDesignIds' => $product->designs->pluck('id')->all(),
    'useOldInput' => false,
    'formClass' => 'space-y-6',
    'footerClasses' => 'flex justify-end space-x-3 pt-4 border-t border-gray-200'
])
@include('admin.products.partials.pricing-scripts', ['fieldPrefix' => 'edit'])
@include('admin.products.partials.hierarchy-scripts', [
    'fieldPrefix' => 'edit',
    'hierarchy' => $productHierarchy,
])
@include('admin.products.partials.select-all-scripts')