<form id="createForm" method="POST" action="{{ route('admin.patterns.store') }}" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Pattern Name</label>
                <input type="text" id="create_name" name="name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Assign to Products (Optional)</label>
                <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    @foreach($products as $product)
                    <label class="flex items-center space-x-2 py-1">
                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                               class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        <span class="text-sm text-gray-700">{{ $product->name }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-1">Select products that can use this pattern</p>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" checked
                       class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label for="create_svg_content" class="block text-sm font-medium text-gray-700 mb-2">SVG Content</label>
                <textarea id="create_svg_content" name="svg_content" rows="12"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-mono text-sm"
                          placeholder="Paste your SVG code here..." required></textarea>
                <p class="text-xs text-gray-500 mt-1">Paste the complete SVG code including &lt;svg&gt; tags</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SVG Preview</label>
                <div id="svg-preview" class="border border-gray-300 rounded-lg p-4 bg-gray-50 min-h-32 flex items-center justify-center">
                    <span class="text-gray-500 text-sm">SVG preview will appear here</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeCreateModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
            Create Pattern
        </button>
    </div>
</form>

<script>
document.getElementById('create_svg_content').addEventListener('input', function() {
    const svgContent = this.value.trim();
    const previewDiv = document.getElementById('svg-preview');

    if (svgContent) {
        // Basic validation - check if it contains SVG tags
        if (svgContent.includes('<svg') && svgContent.includes('</svg>')) {
            previewDiv.innerHTML = svgContent;
            // Set max dimensions for preview
            const svgElement = previewDiv.querySelector('svg');
            if (svgElement) {
                svgElement.style.maxWidth = '100%';
                svgElement.style.maxHeight = '200px';
                svgElement.style.width = 'auto';
                svgElement.style.height = 'auto';
            }
        } else {
            previewDiv.innerHTML = '<span class="text-red-500 text-sm">Invalid SVG content</span>';
        }
    } else {
        previewDiv.innerHTML = '<span class="text-gray-500 text-sm">SVG preview will appear here</span>';
    }
});
</script>