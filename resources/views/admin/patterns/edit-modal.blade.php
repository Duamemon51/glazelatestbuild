<form method="POST" action="{{ route('admin.patterns.update', $pattern) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Pattern Name</label>
        <input type="text" id="edit_name" name="name" value="{{ old('name', $pattern->name) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="edit_svg_content" class="block text-sm font-medium text-gray-700 mb-2">SVG Content</label>
        <textarea id="edit_svg_content" name="svg_content" rows="8" required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"
                  placeholder="<svg>...</svg>">{{ old('svg_content', $pattern->svg_content) }}</textarea>
        @error('svg_content')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- SVG Preview -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">SVG Preview</label>
        <div id="edit-svg-preview" class="w-full h-32 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
            {!! $pattern->svg_content !!}
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Assign to Products</label>
        <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-3">
            @foreach($products as $product)
            <label class="flex items-center space-x-2 py-1">
                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                       {{ $pattern->products->contains($product->id) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-gray-700">{{ $product->name }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <div class="flex items-center">
        <input type="checkbox" id="edit_is_active" name="is_active" value="1" {{ old('is_active', $pattern->is_active) ? 'checked' : '' }}
               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        <label for="edit_is_active" class="ml-2 text-sm text-gray-700">Active</label>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeEditModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Update Pattern
        </button>
    </div>
</form>

<script>
// Update preview when SVG content changes
document.getElementById('edit_svg_content').addEventListener('input', function() {
    const svgContent = this.value.trim();
    const previewDiv = document.getElementById('edit-svg-preview');

    if (svgContent) {
        if (svgContent.includes('<svg') && svgContent.includes('</svg>')) {
            previewDiv.innerHTML = svgContent;
            const svgElement = previewDiv.querySelector('svg');
            if (svgElement) {
                svgElement.style.maxWidth = '100%';
                svgElement.style.maxHeight = '100%';
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