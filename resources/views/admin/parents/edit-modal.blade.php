<form method="POST" action="{{ route('admin.parents.update', $parent) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
        <input type="text" id="edit_name" name="name" value="{{ old('name', $parent->name) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-2">Default Image</label>
        <input type="file" id="edit_image" name="image" accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @if($parent->image)
            <p class="text-xs text-gray-600 mt-1">Current: {{ $parent->image }}</p>
            <div class="mt-2">
                <img src="{{ asset('storage/' . $parent->image) }}" alt="Current image" class="w-20 h-20 object-cover rounded border">
            </div>
        @endif
        <p class="text-xs text-gray-500 mt-1">Fallback image for any area without a dedicated asset.</p>
        @error('image')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="edit_home_image" class="block text-sm font-medium text-gray-700 mb-2">Homepage Image</label>
        <input type="file" id="edit_home_image" name="home_image" accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @if($parent->home_image)
            <p class="text-xs text-gray-600 mt-1">Current: {{ $parent->home_image }}</p>
            <div class="mt-2">
                <img src="{{ asset('storage/' . $parent->home_image) }}" alt="Homepage image" class="w-20 h-20 object-cover rounded border">
            </div>
        @endif
        <p class="text-xs text-gray-500 mt-1">Displayed in the homepage category carousel.</p>
        @error('home_image')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="edit_category_image" class="block text-sm font-medium text-gray-700 mb-2">Category Page Hero Image</label>
        <input type="file" id="edit_category_image" name="category_image" accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @if($parent->category_image)
            <p class="text-xs text-gray-600 mt-1">Current: {{ $parent->category_image }}</p>
            <div class="mt-2">
                <img src="{{ asset('storage/' . $parent->category_image) }}" alt="Category hero image" class="w-20 h-20 object-cover rounded border">
            </div>
        @endif
        <p class="text-xs text-gray-500 mt-1">Shown at the top of the category page.</p>
        @error('category_image')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Category Stats (Read-only) -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-medium text-gray-700 mb-2">Category Statistics</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Product Types:</span>
                <span class="font-medium text-gray-900">{{ $parent->productTypes->count() }}</span>
            </div>
            <div>
                <span class="text-gray-600">Total Products:</span>
                <span class="font-medium text-gray-900">{{ $parent->productTypes->sum(fn($type) => $type->products->count()) }}</span>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeEditModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Update Category
        </button>
    </div>
</form>