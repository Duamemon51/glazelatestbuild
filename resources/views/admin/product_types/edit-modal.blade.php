<form method="POST" action="{{ route('admin.product-types.update', $productType) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="space-y-4">
        <div>
            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Product Type Name</label>
            <input type="text" id="edit_name" name="name" value="{{ old('name', $productType->name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="edit_subcategory_id" class="block text-sm font-medium text-gray-700 mb-2">Subcategory</label>
            <select id="edit_subcategory_id" name="subcategory_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Subcategory</option>
                @foreach($parentCategories as $parentCategory)
                    <optgroup label="{{ $parentCategory->name }}">
                        @foreach($parentCategory->subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}"
                                {{ old('subcategory_id', $productType->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                            {{ $subcategory->name }}
                        </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('subcategory_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-2">Product Type Image</label>
            <input type="file" id="edit_image" name="image" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if($productType->image)
                <p class="text-xs text-gray-600 mt-1">Current: {{ $productType->image }}</p>
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $productType->image) }}" alt="Current image" class="w-20 h-20 object-cover rounded border">
                </div>
            @endif
            @error('image')
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
            Update Product Type
        </button>
    </div>
</form>