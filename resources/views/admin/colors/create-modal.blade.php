<!-- Modal Form for Color Creation -->
<form id="createModalForm" action="{{ route('admin.colors.store') }}" method="POST" class="space-y-4">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="color_category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    id="color_category_id" 
                    name="color_category_id" 
                    required>
                <option value="">Select Category</option>
                @foreach(\App\Models\ColorCategory::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="coloring_system" class="block text-sm font-medium text-gray-700 mb-1">Coloring System *</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    id="coloring_system" 
                    name="coloring_system" 
                    required>
                <option value="">Select System</option>
                <option value="pantone_coated">Pantone Coated</option>
                <option value="pantone_uncoated">Pantone Uncoated</option>
                <option value="hks_k">HKS K</option>
                <option value="hks_n">HKS N</option>
                <option value="ral">RAL</option>
            </select>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Color Name *</label>
            <input type="text" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   id="name" 
                   name="name" 
                   required>
        </div>
        
        <div>
            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Color Code *</label>
            <input type="text" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   id="code" 
                   name="code" 
                   placeholder="e.g., PMS 186 C, RAL 3020"
                   required>
        </div>
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">RGB Values *</label>
        <div class="grid grid-cols-4 gap-3">
            <div>
                <label for="rgb_r" class="block text-xs text-gray-500 mb-1">Red (0-255)</label>
                <input type="number" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       id="rgb_r" 
                       name="rgb_r" 
                       min="0" 
                       max="255" 
                       value="0" 
                       required>
            </div>
            <div>
                <label for="rgb_g" class="block text-xs text-gray-500 mb-1">Green (0-255)</label>
                <input type="number" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       id="rgb_g" 
                       name="rgb_g" 
                       min="0" 
                       max="255" 
                       value="0" 
                       required>
            </div>
            <div>
                <label for="rgb_b" class="block text-xs text-gray-500 mb-1">Blue (0-255)</label>
                <input type="number" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       id="rgb_b" 
                       name="rgb_b" 
                       min="0" 
                       max="255" 
                       value="0" 
                       required>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Preview</label>
                <div id="colorPreview" 
                     class="w-full h-10 border border-gray-300 rounded-md"
                     style="background-color: rgb(0, 0, 0);"></div>
            </div>
        </div>
    </div>
    
    <div>
        <label for="closest_association" class="block text-sm font-medium text-gray-700 mb-1">Closest Association</label>
        <input type="text" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
               id="closest_association" 
               name="closest_association"
               placeholder="e.g., Ocean Blue, Forest Green">
    </div>
    
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                  id="description" 
                  name="description" 
                  rows="2"
                  placeholder="Optional description of this color..."></textarea>
    </div>
    
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button type="button" 
                onclick="closeCreateModal()" 
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Cancel
        </button>
        <button type="submit" 
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-save mr-1"></i>Create Color
        </button>
    </div>
</form>

<script>
// Color preview functionality
function updateColorPreview() {
    const r = document.getElementById('rgb_r').value || 0;
    const g = document.getElementById('rgb_g').value || 0;
    const b = document.getElementById('rgb_b').value || 0;
    
    const color = `rgb(${r}, ${g}, ${b})`;
    document.getElementById('colorPreview').style.backgroundColor = color;
}

// Update preview on input change
document.getElementById('rgb_r').addEventListener('input', updateColorPreview);
document.getElementById('rgb_g').addEventListener('input', updateColorPreview);
document.getElementById('rgb_b').addEventListener('input', updateColorPreview);

// Form submission
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
            location.reload(); // Refresh to show new color
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

// Initial preview update
updateColorPreview();
</script>