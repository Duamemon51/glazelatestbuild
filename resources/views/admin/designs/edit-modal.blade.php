<form id="editForm" method="POST" action="{{ route('admin.designs.update', $design->id) }}" enctype="multipart/form-data" class="space-y-6" onsubmit="handleEditSubmit(event)">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Design Name</label>
                <input type="text" id="edit_name" name="name" value="{{ old('name', $design->name) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label for="edit_front_image" class="block text-sm font-medium text-gray-700 mb-2">Front Image</label>
                <input type="file" id="edit_front_image" name="front_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                @if($design->front_image)
                    <p class="text-sm text-gray-500 mt-1">Current: {{ basename($design->front_image) }}</p>
                @endif
            </div>

            <div>
                <label for="edit_back_image" class="block text-sm font-medium text-gray-700 mb-2">Back Image</label>
                <input type="file" id="edit_back_image" name="back_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                @if($design->back_image)
                    <p class="text-sm text-gray-500 mt-1">Current: {{ basename($design->back_image) }}</p>
                @endif
            </div>

            <div>
                <label for="edit_left_image" class="block text-sm font-medium text-gray-700 mb-2">Left Image</label>
                <input type="file" id="edit_left_image" name="left_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                @if($design->left_image)
                    <p class="text-sm text-gray-500 mt-1">Current: {{ basename($design->left_image) }}</p>
                @endif
            </div>

            <div>
                <label for="edit_right_image" class="block text-sm font-medium text-gray-700 mb-2">Right Image</label>
                <input type="file" id="edit_right_image" name="right_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                @if($design->right_image)
                    <p class="text-sm text-gray-500 mt-1">Current: {{ basename($design->right_image) }}</p>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label for="edit_preview_img" class="block text-sm font-medium text-gray-700 mb-2">Preview Image</label>
                <input type="file" id="edit_preview_img" name="preview_img" accept="image/*" onchange="previewEditImage(event)"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                @if($design->preview_img)
                    <p class="text-sm text-gray-500 mt-1">Current: {{ basename($design->preview_img) }}</p>
                    <img id="editPreview" src="{{ asset('storage/' . $design->preview_img) }}" alt="Current Preview" class="max-w-32 mt-2 rounded-lg">
                @else
                    <img id="editPreview" src="#" alt="Preview Image" class="hidden max-w-32 mt-2 rounded-lg">
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Attach Products</label>
                <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    @foreach($products as $product)
                    <label class="flex items-center space-x-2 py-1">
                        <input type="checkbox" name="products[]" value="{{ $product->id }}"
                               {{ $design->products->contains($product->id) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span class="text-sm text-gray-700">{{ $product->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $design->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_unique" value="1" {{ old('is_unique', $design->is_unique) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm text-gray-700">Unique Design</span>
                </label>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeEditModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
            Update Design
        </button>
    </div>
</form>

<script>
function previewEditImage(event) {
    const preview = document.getElementById('editPreview');
    const file = event.target.files[0];
    if(file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    }
}

function handleEditSubmit(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
    .then(response => {
        if (response.redirected) {
            // Success - redirect to the designs index page
            window.location.href = response.url;
        } else {
            return response.text();
        }
    })
    .then(data => {
        if (data) {
            // If there's a response body, it might be an error or validation message
            document.getElementById('editModalContent').innerHTML = data;
        }
    })
    .catch(error => {
        console.error('Error updating design:', error);
        alert('Error updating design. Please try again.');
    });
}
</script>