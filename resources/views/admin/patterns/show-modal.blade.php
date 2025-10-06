<div class="space-y-6">
    <!-- Pattern Preview -->
    <div class="flex items-center justify-center p-8 bg-gray-50 rounded-lg">
        <div class="w-64 h-64 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-white">
            {!! $pattern->svg_content !!}
        </div>
    </div>

    <!-- Pattern Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pattern Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $pattern->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <p class="mt-1">
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $pattern->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $pattern->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Created</label>
                <p class="mt-1 text-sm text-gray-900">{{ $pattern->created_at->format('M d, Y H:i') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                <p class="mt-1 text-sm text-gray-900">{{ $pattern->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Associated Products</label>
            @if($pattern->products->count() > 0)
                <div class="space-y-2 max-h-32 overflow-y-auto">
                    @foreach($pattern->products as $product)
                        <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg">
                            <span class="text-sm text-blue-800">{{ $product->name }}</span>
                            <span class="text-xs text-blue-600">${{ number_format($product->price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="mt-2 text-sm text-gray-600">{{ $pattern->products->count() }} product{{ $pattern->products->count() !== 1 ? 's' : '' }} assigned</p>
            @else
                <p class="text-sm text-gray-500 italic">No products assigned</p>
            @endif
        </div>
    </div>

    <!-- SVG Code -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">SVG Code</label>
        <div class="bg-gray-900 rounded-lg p-4 max-h-40 overflow-y-auto">
            <pre class="text-green-400 text-xs"><code>{{ htmlspecialchars($pattern->svg_content) }}</code></pre>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button onclick="closeViewModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Close
        </button>
        <button onclick="openEditModal({{ $pattern->id }})"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Edit Pattern
        </button>
    </div>
</div>