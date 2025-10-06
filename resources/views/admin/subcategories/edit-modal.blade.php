<form method="POST" action="{{ route('admin.subcategories.update', $subcategory) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="space-y-4">
        <div>
            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Subcategory Name</label>
            <input type="text" id="edit_name" name="name" value="{{ old('name', $subcategory->name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="edit_parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
            <select id="edit_parent_category_id" name="parent_category_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Parent Category</option>
                @foreach($parentCategories as $parentCategory)
                <option value="{{ $parentCategory->id }}"
                        {{ old('parent_category_id', $subcategory->parent_category_id) == $parentCategory->id ? 'selected' : '' }}>
                    {{ $parentCategory->name }}
                </option>
                @endforeach
            </select>
            @error('parent_category_id')
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
            Update Subcategory
        </button>
    </div>
</form>