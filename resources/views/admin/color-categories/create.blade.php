@if(request()->ajax())
    <form id="createModalForm" action="{{ route('admin.color-categories.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="e.g., Primary Colors, Warm Colors, etc."
                       required>
                <p class="text-xs text-gray-500 mt-1">Choose a descriptive name for this color category</p>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Describe this color category (optional)"></textarea>
                <p class="text-xs text-gray-500 mt-1">Optional description to help organize your colors</p>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
            <button type="button" onclick="closeCreateModal()" 
                    class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                Cancel
            </button>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Create Category
            </button>
        </div>
    </form>

    <script>
    // Wait for DOM elements to be available
    setTimeout(function() {
        const form = document.getElementById('createModalForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
                submitBtn.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeCreateModal();
                // Show success notification
                showNotification('✅ ' + data.message, 'success');
                // Refresh page after short delay to show server-side success message
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('❌ ' + (data.message || 'An error occurred'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ An error occurred while creating the category', 'error');
        })
        .finally(() => {
            // Restore button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
            });
        }
    }, 100); // Wait 100ms for DOM elements to be available
    </script>
@else
    @extends('layouts.admin')

    @section('title', 'Create Color Category')

    @section('content')
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Create Color Category</h1>
                        <p class="text-gray-600">Add a new category to organize your colors</p>
                    </div>

                    <form action="{{ route('admin.color-categories.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                                       value="{{ old('name') }}"
                                       placeholder="e.g., Primary Colors, Warm Colors, etc."
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="description" 
                                          name="description" 
                                          rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                          placeholder="Describe this color category (optional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.color-categories.index') }}" 
                               class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Categories
                            </a>
                            <div class="space-x-3">
                                <a href="{{ route('admin.color-categories.index') }}" 
                                   class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i>Create Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Guidelines
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Naming Tips</h4>
                                <p class="text-sm text-gray-600">Use descriptive names like "Primary Colors", "Pastels", or "Corporate Colors".</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Organization</h4>
                                <p class="text-sm text-gray-600">Categories help organize your color library and make it easier to find specific colors.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-paint-brush text-purple-500 mr-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Examples</h4>
                                <p class="text-sm text-gray-600">Brand Colors, Web Safe Colors, Print Colors, Seasonal Colors.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@endif