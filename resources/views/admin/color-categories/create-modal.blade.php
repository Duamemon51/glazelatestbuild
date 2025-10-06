<!-- Modal Form for Color Category Creation -->
<form id="createModalForm" action="{{ route('admin.color-categories.store') }}" method="POST" class="space-y-4">
    @csrf
    
    <div class="grid grid-cols-1 gap-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
            <input type="text" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   id="name" 
                   name="name" 
                   required>
        </div>
        
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      id="description" 
                      name="description" 
                      rows="3"
                      placeholder="Optional description for this color category..."></textarea>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" 
                onclick="closeCreateModal()" 
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Cancel
        </button>
        <button type="submit" 
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-save mr-1"></i>Create Category
        </button>
    </div>
</form>

<script>
document.getElementById('createModalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            closeCreateModal();
            location.reload(); // Refresh to show new category
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'An error occurred');
            });
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
        console.error('Error:', error);
    });
});
</script>