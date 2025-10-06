            @include('admin.products.partials.form', [
                'formId' => 'createModalForm',
                'action' => route('admin.products.store'),
                'method' => 'POST',
                'fieldPrefix' => 'createModal',
                'showCancel' => true,
                'cancelHandler' => 'closeCreateModal()',
                'cancelLabel' => 'Cancel',
                'cancelButtonClasses' => 'px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors',
                'submitLabel' => 'Create Product',
                'submitButtonClasses' => 'px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors',
                'product' => null,
                'productTypes' => $productTypes,
                'parentCategories' => $parentCategories,
                'subcategories' => $subcategories,
                'patterns' => $patterns,
                'designs' => $designs,
                'useOldInput' => false,
                'formClass' => 'space-y-6',
                'footerClasses' => 'flex justify-end space-x-3 pt-4 border-t border-gray-200'
            ])
@include('admin.products.partials.pricing-scripts', ['fieldPrefix' => 'createModal'])
@include('admin.products.partials.hierarchy-scripts', [
    'fieldPrefix' => 'createModal',
    'hierarchy' => $productHierarchy,
])
@include('admin.products.partials.select-all-scripts')