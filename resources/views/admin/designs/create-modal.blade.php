<form id="createForm" method="POST" action="{{ route('admin.designs.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Design Name</label>
                <input type="text" id="create_name" name="name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label for="create_front_image" class="block text-sm font-medium text-gray-700 mb-2">Front Image</label>
                <input type="file" id="create_front_image" name="front_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label for="create_back_image" class="block text-sm font-medium text-gray-700 mb-2">Back Image</label>
                <input type="file" id="create_back_image" name="back_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label for="create_left_image" class="block text-sm font-medium text-gray-700 mb-2">Left Image</label>
                <input type="file" id="create_left_image" name="left_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label for="create_right_image" class="block text-sm font-medium text-gray-700 mb-2">Right Image</label>
                <input type="file" id="create_right_image" name="right_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label for="create_preview_img" class="block text-sm font-medium text-gray-700 mb-2">Preview Image</label>
                <input type="file" id="create_preview_img" name="preview_img" accept="image/*" onchange="previewImage(event)"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <img id="preview" src="#" alt="Preview Image" class="hidden max-w-32 mt-2 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Attach Products</label>
                <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    @foreach($products as $product)
                    <label class="flex items-center space-x-2 py-1">
                        <input type="checkbox" name="products[]" value="{{ $product->id }}"
                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span class="text-sm text-gray-700">{{ $product->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_unique" value="1"
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm text-gray-700">Unique Design</span>
                </label>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeCreateModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
            Create Design
        </button>
    </div>
</form>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if(file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    }
}
</script>