<div class="space-y-6">
    <!-- Product Image and Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-6xl"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h3>
                <p class="text-gray-600">Product ID: {{ $product->id }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Type</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($product->productType)
                            {{ $product->productType->name }}
                            @if($product->productType->subcategory)
                                ({{ $product->productType->subcategory->name }})
                            @endif
                        @else
                            No type assigned
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Pricing</label>
                    <div class="mt-1">
                        <p class="text-sm text-gray-900 font-medium">Base Price: ${{ number_format($product->price, 2) }}</p>
                        @if($product->prices && is_array($product->prices) && count($product->prices) > 0)
                            <div class="mt-2">
                                <p class="text-xs text-gray-600 mb-1">Tiered Pricing:</p>
                                <div class="space-y-1">
                                    @foreach($product->getPricingTiers() as $tier)
                                        <p class="text-xs text-gray-700">
                                            {{ $tier['min_quantity'] }} or more: ${{ number_format($tier['price'], 2) }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $product->quantity }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </p>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">3D Model Asset</label>
                    @if($product->model_3d)
                        <p class="mt-1 text-sm text-gray-900 flex items-center gap-2">
                            <i class="fas fa-cube text-blue-500"></i>
                            {{ basename($product->model_3d) }}
                        </p>
                        <div class="mt-2 flex flex-wrap gap-3 text-sm">
                            <a href="{{ route('admin.products.show3D', $product) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                View / download asset
                            </a>
                            @php
                                $ext = strtolower(pathinfo($product->model_3d, PATHINFO_EXTENSION));
                                $previewable = in_array($ext, ['glb','gltf']);
                            @endphp
                            <span class="text-gray-500">
                                Preview {{ $previewable ? 'available in viewer' : 'not supported; download to inspect' }}
                            </span>
                        </div>
                    @else
                        <p class="mt-1 text-sm text-gray-500 italic">No 3D asset uploaded</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Details -->
    @if($product->details)
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Details</label>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-900">{{ $product->details }}</p>
        </div>
    </div>
    @endif

    <!-- Assigned Patterns -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Patterns</label>
        @if($product->patterns->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($product->patterns as $pattern)
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded border flex items-center justify-center">
                            @if($pattern->svg_content)
                                <div class="w-8 h-8 flex items-center justify-center">
                                    {!! $pattern->svg_content !!}
                                </div>
                            @else
                                <i class="fas fa-shapes text-gray-400"></i>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $pattern->name }}</p>
                            <p class="text-xs text-gray-600">{{ $pattern->is_active ? 'Active' : 'Inactive' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">No patterns assigned</p>
        @endif
    </div>

    <!-- Assigned Designs -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Designs</label>
        @if($product->designs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($product->designs as $design)
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded border flex items-center justify-center">
                            @if($design->preview_img)
                                <img src="{{ asset('storage/' . $design->preview_img) }}" 
                                     alt="{{ $design->name }}" 
                                     class="w-10 h-10 object-cover rounded">
                            @else
                                <i class="fas fa-palette text-gray-400"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $design->name }}</p>
                            <div class="flex items-center space-x-2 text-xs text-gray-600">
                                <span>{{ $design->is_active ? 'Active' : 'Inactive' }}</span>
                                @if($design->is_unique)
                                    <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-800 rounded">Unique</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">No designs assigned</p>
        @endif
    </div>

    <!-- Timestamps -->
    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
        <div>
            <label class="block text-sm font-medium text-gray-700">Created</label>
            <p class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
            <p class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('M d, Y H:i') }}</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button onclick="closeViewModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Close
        </button>
        <button onclick="openEditModal({{ $product->id }})"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Edit Product
        </button>
    </div>
</div>