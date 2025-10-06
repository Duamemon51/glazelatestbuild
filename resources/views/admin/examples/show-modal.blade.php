<div class="space-y-6">
    <!-- Example Image and Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                @if($example->image)
                    <img src="{{ asset('storage/' . $example->image) }}" alt="{{ $example->name }}"
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
                <h3 class="text-2xl font-bold text-gray-900">{{ $example->name }}</h3>
                <p class="text-gray-600">Example ID: {{ $example->id }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Type</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $example->productType->name ?? 'No product type assigned' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">3D Model</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($example->model_3d)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-cube mr-1"></i>
                                Available
                            </span>
                            <a href="{{ route('admin.examples.show3D', $example->id) }}"
                               class="ml-2 inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                View 3D Model
                            </a>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-minus mr-1"></i>
                                Not Available
                            </span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $example->created_at->format('M d, Y \a\t g:i A') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $example->updated_at->format('M d, Y \a\t g:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Information -->
    <div class="border-t border-gray-200 pt-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Related Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <h5 class="font-medium text-gray-900 mb-2">Product Type Details</h5>
                @if($example->productType)
                    <p class="text-sm text-gray-600">Name: {{ $example->productType->name }}</p>
                    @if($example->productType->subcategory)
                        <p class="text-sm text-gray-600">Subcategory: {{ $example->productType->subcategory->name }}</p>
                        @if($example->productType->subcategory->parentCategory)
                            <p class="text-sm text-gray-600">Parent Category: {{ $example->productType->subcategory->parentCategory->name }}</p>
                        @endif
                    @endif
                @else
                    <p class="text-sm text-gray-600">No product type assigned</p>
                @endif
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <h5 class="font-medium text-gray-900 mb-2">File Information</h5>
                @if($example->image)
                    <p class="text-sm text-gray-600">Image: {{ basename($example->image) }}</p>
                @endif
                @if($example->model_3d)
                    <p class="text-sm text-gray-600">3D Model: {{ basename($example->model_3d) }}</p>
                @endif
                @if(!$example->image && !$example->model_3d)
                    <p class="text-sm text-gray-600">No files uploaded</p>
                @endif
            </div>
        </div>
    </div>
</div>