@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Edit Pattern: {{ $pattern->name }}</h1>
            <a href="{{ route('admin.patterns.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Patterns
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('admin.patterns.update', $pattern) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Pattern Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $pattern->name) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="product_ids" class="block text-gray-700 text-sm font-bold mb-2">Assign to Products (Optional)</label>
                    <div class="max-h-40 overflow-y-auto border rounded p-2">
                        @foreach($products as $product)
                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                       {{ $pattern->products->contains($product->id) ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">{{ $product->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-gray-600 text-xs mt-1">Select products that can use this pattern</p>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $pattern->is_active) ? 'checked' : '' }}
                               class="mr-2">
                        <span class="text-gray-700 text-sm font-bold">Active</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label for="svg_content" class="block text-gray-700 text-sm font-bold mb-2">SVG Content *</label>
                    <textarea name="svg_content" id="svg_content" rows="20"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline font-mono text-sm @error('svg_content') border-red-500 @enderror"
                              placeholder="Paste your SVG code here..." required>{{ old('svg_content', $pattern->svg_content) }}</textarea>
                    @error('svg_content')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-600 text-xs mt-1">Paste the complete SVG code including &lt;svg&gt; tags</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">SVG Preview</label>
                    <div id="svg-preview" class="border rounded p-4 bg-gray-50 min-h-32 flex items-center justify-center">
                        {!! $pattern->svg_content !!}
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Pattern
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('svg_content').addEventListener('input', function() {
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
            previewDiv.innerHTML = '<span class="text-red-500">Invalid SVG content</span>';
        }
    } else {
        previewDiv.innerHTML = '<span class="text-gray-500">SVG preview will appear here</span>';
    }
});
</script>
@endsection