<form id="createForm" method="POST" action="{{ route('admin.subcategories.store') }}" class="space-y-4">
    @csrf
    <div>
        <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Sub Category Name</label>
        <input type="text" id="create_name" name="name" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
    </div>

    <div>
        <label for="create_parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
        <select id="create_parent_category_id" name="parent_category_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">-- Select Parent Category --</option>
            @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeCreateModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Create Sub Category
        </button>
    </div>
</form>