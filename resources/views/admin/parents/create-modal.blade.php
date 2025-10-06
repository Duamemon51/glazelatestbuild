<form id="createForm" method="POST" action="{{ route('admin.parents.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div>
        <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
        <input type="text" id="create_name" name="name" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
        <label for="create_image" class="block text-sm font-medium text-gray-700 mb-2">Default Image</label>
        <input type="file" id="create_image" name="image" accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        <p class="text-xs text-gray-500 mt-1">Used anywhere a fallback image is required.</p>
    </div>

    <div>
        <label for="create_home_image" class="block text-sm font-medium text-gray-700 mb-2">Homepage Image</label>
        <input type="file" id="create_home_image" name="home_image" accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        <p class="text-xs text-gray-500 mt-1">Displayed on the homepage category carousel.</p>
    </div>

    <div>
        <label for="create_category_image" class="block text-sm font-medium text-gray-700 mb-2">Category Page Hero Image</label>
        <input type="file" id="create_category_image" name="category_image" accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        <p class="text-xs text-gray-500 mt-1">Shown as the hero image on the category page.</p>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeCreateModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            Create Category
        </button>
    </div>
</form>