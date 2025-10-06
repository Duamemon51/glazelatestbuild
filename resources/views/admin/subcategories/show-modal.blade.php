<div class="space-y-6">
    <!-- Subcategory Info -->
    <div class="bg-gray-50 rounded-lg p-6">
        <div class="flex items-center justify-center mb-4">
            <div class="w-16 h-16 bg-white rounded-full border-2 border-gray-300 flex items-center justify-center">
                <i class="fas fa-layer-group text-2xl text-gray-400"></i>
            </div>
        </div>

        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $subcategory->name }}</h3>
            <p class="text-gray-600">Subcategory ID: {{ $subcategory->id }}</p>
        </div>
    </div>

    <!-- Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Parent Category</label>
            <p class="mt-1 text-sm text-gray-900">
                {{ $subcategory->parentCategory->name ?? 'No parent category assigned' }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Product Types Count</label>
            <p class="mt-1 text-sm text-gray-900">
                {{ $subcategory->productTypes->count() }} product types
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Created</label>
            <p class="mt-1 text-sm text-gray-900">
                {{ $subcategory->created_at->format('M d, Y \a\t g:i A') }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
            <p class="mt-1 text-sm text-gray-900">
                {{ $subcategory->updated_at->format('M d, Y \a\t g:i A') }}
            </p>
        </div>
    </div>

    <!-- Related Product Types -->
    <div class="border-t border-gray-200 pt-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Product Types in this Subcategory</h4>
        @if($subcategory->productTypes && $subcategory->productTypes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($subcategory->productTypes as $productType)
                <div class="bg-gray-50 rounded-lg p-3">
                    <h5 class="font-medium text-gray-900">{{ $productType->name }}</h5>
                    @if($productType->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $productType->image) }}" alt="{{ $productType->name }}" class="w-full h-16 object-cover rounded">
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No product types in this subcategory yet.</p>
        @endif
    </div>
</div>