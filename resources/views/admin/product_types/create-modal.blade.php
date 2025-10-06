<form id="createForm" method="POST" action="{{ route('admin.product-types.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div>
        <label for="create_parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
        <select id="create_parent_category_id" name="parent_category_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            <option value="">-- Select Parent Category --</option>
            @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="create_subcategory_id" class="block text-sm font-medium text-gray-700 mb-2">Subcategory</label>
        <select id="create_subcategory_id" name="subcategory_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            <option value="">-- Select Subcategory --</option>
        </select>
    </div>

    <div>
        <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Product Type Name</label>
        <input type="text" id="create_name" name="name" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
    </div>

    <div>
        <label for="create_image" class="block text-sm font-medium text-gray-700 mb-2">Product Type Image</label>
        <input type="file" id="create_image" name="image" accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeCreateModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
            Create Product Type
        </button>
    </div>
</form>

<script>
document.getElementById('create_parent_category_id').addEventListener('change', function() {
    var parentId = this.value;
    var subcategorySelect = document.getElementById('create_subcategory_id');

    if (parentId) {
        fetch('{{ url("admin") }}/subcategories/by-parent/' + parentId)
            .then(response => response.json())
            .then(data => {
                subcategorySelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
                data.forEach(function(subcategory) {
                    var option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    subcategorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);
            });
    } else {
        subcategorySelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
    }
});
</script>