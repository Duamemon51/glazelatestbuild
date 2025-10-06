<form method="POST" action="{{ route('admin.examples.update', $example) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="space-y-4">
        <div>
            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Example Name</label>
            <input type="text" id="edit_name" name="name" value="{{ old('name', $example->name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="edit_product_type_id" class="block text-sm font-medium text-gray-700 mb-2">Product Type</label>
            <select id="edit_product_type_id" name="product_type_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Product Type</option>
                @foreach($productTypes as $type)
                <option value="{{ $type->id }}"
                        {{ old('product_type_id', $example->product_type_id) == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
                @endforeach
            </select>
            @error('product_type_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-2">Example Image</label>
            <input type="file" id="edit_image" name="image" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if($example->image)
                <p class="text-xs text-gray-600 mt-1">Current: {{ $example->image }}</p>
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $example->image) }}" alt="Current image" class="w-20 h-20 object-cover rounded border">
                </div>
            @endif
            @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="edit_model_3d" class="block text-sm font-medium text-gray-700 mb-2">3D Model (.glb or .gltf)</label>
            <input type="file" id="edit_model_3d" name="model_3d" accept=".glb,.gltf"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if($example->model_3d)
                <p class="text-xs text-gray-600 mt-1">Current: {{ basename($example->model_3d) }}</p>
                <a href="{{ route('admin.examples.show3D', $example->id) }}"
                   class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 mt-1">
                    <i class="fas fa-external-link-alt mr-1"></i>
                    View Current 3D Model
                </a>
            @endif
            <p class="text-xs text-gray-500 mt-1">Optional: Upload a new 3D model file (.glb or .gltf format)</p>
            @error('model_3d')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeEditModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Update Example
        </button>
    </div>
</form>