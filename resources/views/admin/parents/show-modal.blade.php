<div class="space-y-6">
    <!-- Category Image and Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Default Image</p>
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    @if($parent->image)
                        <img src="{{ asset('storage/' . $parent->image) }}" alt="{{ $parent->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-6xl"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Homepage Image</p>
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                        @if($parent->home_image)
                            <img src="{{ asset('storage/' . $parent->home_image) }}" alt="{{ $parent->name }} homepage"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-image text-4xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Category Hero Image</p>
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                        @if($parent->category_image)
                            <img src="{{ asset('storage/' . $parent->category_image) }}" alt="{{ $parent->name }} hero"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-image text-4xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $parent->name }}</h3>
                <p class="text-gray-600">Category ID: {{ $parent->id }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Types</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $parent->productTypes->count() }} types</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Associated Product Types -->
    @if($parent->productTypes->count() > 0)
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Associated Product Types</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($parent->productTypes as $type)
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $type->name }}</p>
                        @if($type->image)
                            <p class="text-xs text-gray-600">Has image</p>
                        @else
                            <p class="text-xs text-gray-500">No image</p>
                        @endif
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                        {{ $type->products->count() }} products
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
        <div>
            <label class="block text-sm font-medium text-gray-700">Created</label>
            <p class="mt-1 text-sm text-gray-900">{{ $parent->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
            <p class="mt-1 text-sm text-gray-900">{{ $parent->updated_at->format('M d, Y H:i') }}</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button onclick="closeViewModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Close
        </button>
        <button onclick="openEditModal({{ $parent->id }})"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Edit Category
        </button>
    </div>
</div>