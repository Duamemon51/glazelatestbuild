<div class="space-y-6">
    <!-- Product Type Image and Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                @if($productType->image)
                    <img src="{{ asset('storage/' . $productType->image) }}" alt="{{ $productType->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <i class="fas fa-tag text-6xl"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $productType->name }}</h3>
                <p class="text-gray-600">Product Type ID: {{ $productType->id }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subcategory</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $productType->subcategory->name ?? 'No subcategory assigned' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $productType->subcategory->parentCategory->name ?? 'No parent category' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $productType->created_at->format('M d, Y \a\t g:i A') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $productType->updated_at->format('M d, Y \a\t g:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="border-t border-gray-200 pt-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Related Products</h4>
        @if($productType->products && $productType->products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($productType->products->take(6) as $product)
                <div class="bg-gray-50 rounded-lg p-3">
                    <h5 class="font-medium text-gray-900">{{ $product->name }}</h5>
                    <p class="text-sm text-gray-600">Price: ${{ number_format($product->price, 2) }}</p>
                    <p class="text-sm text-gray-600">Stock: {{ $product->quantity }}</p>
                </div>
                @endforeach
            </div>
            @if($productType->products->count() > 6)
                <p class="text-sm text-gray-600 mt-2">And {{ $productType->products->count() - 6 }} more products...</p>
            @endif
        @else
            <p class="text-gray-600">No products assigned to this product type yet.</p>
        @endif
    </div>
</div>